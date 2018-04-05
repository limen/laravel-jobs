<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Examples\Jobs;

use Limen\Jobs\JobsConst;

class VisitHuangshanJob extends BaseJob
{
    protected $name = 'visit_Huangshan';

    protected function doStuff()
    {
        $statuses = [
            JobsConst::JOB_STATUS_FINISHED,
            JobsConst::JOB_STATUS_WAITING_FEEDBACK,
            JobsConst::JOB_STATUS_FAILED,
            JobsConst::JOB_STATUS_WAITING_RETRY,
        ];
        $card = time() % 4;

        return $statuses[$card];
    }
}