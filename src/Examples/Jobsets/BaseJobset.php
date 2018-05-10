<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/4/3
 */

namespace Limen\Laravel\Jobs\Examples\Jobsets;

use Limen\Jobs\Contracts\BaseJobset as Jobset;
use Limen\Jobs\JobsConst;
use Limen\Laravel\Jobs\Examples\Jobs\BaseJob;
use Limen\Laravel\Jobs\Models\JobModel;
use Limen\Laravel\Jobs\Models\JobsetModel;

abstract class BaseJobset extends Jobset
{
    protected function findModel($id)
    {
        return JobsetModel::find($id);
    }

    protected function makeModel($attributes = [])
    {
        $now = date('Y-m-d H:i:s');

        $model = new JobsetModel();
        $model->setName($this->name);
        $model->setStatus(JobsConst::JOB_SET_STATUS_DEFAULT);
        $model->setTryAt($now);
        $model->setCreatedAt($now);
        $model->setUpdatedAt($now);

        foreach ($attributes as $attr => $value) {
            $model->setAttribute($attr, $value);
        }

        $model->persist();

        return $model;
    }

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
}
