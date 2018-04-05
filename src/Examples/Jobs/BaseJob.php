<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/4/3
 */

namespace Limen\Laravel\Jobs\Examples\Jobs;

use Limen\Jobs\Contracts\BaseJob as Job;
use Limen\Jobs\Contracts\JobModelInterface;
use Limen\Jobs\JobsConst;
use Limen\Laravel\Jobs\Models\JobModel;

abstract class BaseJob extends Job
{
    public function makeModel($jobsetId)
    {
        $model = new JobModel();
        $model->setName($this->name);
        $model->setJobsetId($jobsetId);
        $model->setStatus(JobsConst::JOB_SET_STATUS_DEFAULT);
        $model->setTryAt($this->getFirstTryAt());
        $model->persist();

        return $model;
    }

    protected function findModel($jobsetId)
    {
        return JobModel::findByJobsetIdAndJobName($jobsetId, $this->name);
    }
}