<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Examples\Jobsets;

use Limen\Jobs\Contracts\BaseJob;
use Limen\Laravel\Jobs\Examples\Jobs\NoticeOneJob;
use Limen\Laravel\Jobs\Examples\Jobs\NoticeTwoJob;
use Limen\Laravel\Jobs\Models\JobModel;

class NoticeJobset extends BaseJobset
{
    protected $name = 'notice';

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
        return [];
    }

    protected function getUnorderedJobNames()
    {
        return [
            'notice_one',
            'notice_two',
        ];
    }

    protected function makeJobs()
    {
        $job = NoticeOneJob::make($this->getId());
        $this->updateLocalJob($job);

        $job = NoticeTwoJob::make($this->getId());
        $this->updateLocalJob($job);
    }
}