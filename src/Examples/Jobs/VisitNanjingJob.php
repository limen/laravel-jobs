<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Examples\Jobs;

use Limen\Jobs\JobsConst;

class VisitNanjingJob extends BaseJob
{
    protected $name = 'visit_Nanjing';

    protected function doStuff()
    {
        $statuses = [
            JobsConst::JOB_STATUS_FAILED,
            JobsConst::JOB_STATUS_WAITING_RETRY,
            JobsConst::JOB_STATUS_WAITING_FEEDBACK,
            JobsConst::JOB_STATUS_FINISHED,
        ];
        $card = time() % 4;

        return $statuses[$card];
    }

}