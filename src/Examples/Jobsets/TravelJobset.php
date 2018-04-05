<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Examples\Jobsets;

use Limen\Jobs\Contracts\BaseJob;
use Limen\Laravel\Jobs\Examples\Jobs\VisitBeijingJob;
use Limen\Laravel\Jobs\Examples\Jobs\VisitHuangshanJob;
use Limen\Laravel\Jobs\Examples\Jobs\VisitNanjingJob;
use Limen\Laravel\Jobs\Examples\Jobs\VisitShanghaiJob;
use Limen\Laravel\Jobs\Models\JobModel;
use Limen\Laravel\Jobs\Models\JobsetModel;

class TravelJobset extends BaseJobset
{
    protected $name = 'travel';

    protected function initJobs()
    {
        $jobIds = $this->model->getJobIds();
        foreach ($jobIds as $id) {
            /** @var JobModel $model */
            $model = JobModel::find($id);
            $class = 'Limen\\Laravel\\Jobs\\Examples\\Jobs\\' . ucfirst(camel_case($model->getName())) . 'Job';
            /** @var BaseJob $job */
            $job = new $class();
            $job->setModel($model);
            $this->updateLocalJob($job);
        }
    }

    protected function getOrderedJobNames()
    {
        return [
            'visit_Beijing',
            'visit_Shanghai',
        ];
    }

    protected function getUnorderedJobNames()
    {
        return [
            'visit_Nanjing',
            'visit_Huangshan',
        ];
    }

    protected function makeJobs()
    {
        $job = VisitBeijingJob::make($this->getId());
        $this->updateLocalJob($job);

        $job = VisitShanghaiJob::make($this->getId());
        $this->updateLocalJob($job);

        $job = VisitNanjingJob::make($this->getId());
        $this->updateLocalJob($job);

        $job = VisitHuangshanJob::make($this->getId());
        $this->updateLocalJob($job);
    }
}