<?php

namespace App\Jobs;

use App\Models\Modell;
use App\Models\Train;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\CrossValidation\Reports\AggregateReport;
use Rubix\ML\CrossValidation\Reports\ConfusionMatrix;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\WordCountVectorizer;

class ModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $samples, $labels, $split, $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, $split)
    {
        $this->samples = [];
        $this->labels = [];
        $this->model = $model;
        $this->split = $split;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = Train::select('prepro_train', 'sentiment')->get();
        foreach ($data as $train) {
            $this->samples[] = [$train->prepro_train];
            $this->labels[] = $train->sentiment;
        }
        $dataset = Labeled::build($this->samples, $this->labels);
        [$training, $testing] = $dataset->stratifiedSplit($this->split);
        // $folds = $training->fold(count($this->labels) / 100);
        $estimator = new PersistentModel(
            new Pipeline([
                new WordCountVectorizer(),
                new TfIdfTransformer(),
            ], new GaussianNB()),
            new Filesystem(storage_path() . '/model/' . $this->model . '.model', true)
        );

        $estimator->train($training);

        // $estimator->train($folds[0]);
        // for ($i = 1; $i < count($folds); $i++) {
        //     $estimator->partial($folds[$i]);
        // }

        $predictions = $estimator->predict($testing);

        //Report    
        $report = new AggregateReport([
            new MulticlassBreakdown(),
            new ConfusionMatrix(),
        ]);
        $results = $report->generate($predictions, $testing->labels());
        $estimator->save();

        //Save to DB
        $fix_model = Modell::create([
            'model' => $this->model,
            'split' => $this->split,
            'accuracy' => $results[0]['overall']['accuracy']
        ]);
    }
}