<?php
/**
 * Created by PhpStorm.
 * User: lim
 * Date: 2018/3/17
 * Time: 12:05
 */

use Illuminate\Support\Facades\DB;
use Limen\Jobs\Helper;
use Limen\Laravel\Jobs\Examples\Jobsets\TravelJobset;
use Limen\Laravel\Jobs\Examples\Jobsets\NoticeJobset;
use Limen\Jobs\JobsConst;

/**
 * Class JobsetTest
 */
class JobsetTest extends \Illuminate\Foundation\Testing\TestCase
{
    public function testMakeGet()
    {
        $jobset = TravelJobset::make();
        $this->assertEquals($jobset->getJob('visit_Beijing')->getName(), 'visit_Beijing');
        $this->assertEquals($jobset->getJob('visit_Shanghai')->getName(), 'visit_Shanghai');
        $this->assertEquals($jobset->getJob('visit_Nanjing')->getName(), 'visit_Nanjing');
        $this->assertEquals($jobset->getJob('visit_Huangshan')->getName(), 'visit_Huangshan');

        $jobsetId = $jobset->getId();
        $jobsetCopy = TravelJobset::get($jobsetId);
        $this->assertEquals($jobsetCopy->getJob('visit_Beijing')->getName(), 'visit_Beijing');
        $this->assertEquals($jobsetCopy->getJob('visit_Shanghai')->getName(), 'visit_Shanghai');
        $this->assertEquals($jobsetCopy->getJob('visit_Nanjing')->getName(), 'visit_Nanjing');
        $this->assertEquals($jobsetCopy->getJob('visit_Huangshan')->getName(), 'visit_Huangshan');
        $this->assertEquals($jobsetCopy->getId(), $jobset->getId());
        $this->assertEquals($jobsetCopy->getName(), $jobset->getName());
        $this->assertEquals($jobsetCopy->getJobNames(), $jobset->getJobNames());

        return $jobsetCopy;
    }

    /**
     * @depends testMakeGet
     * @param \Limen\Laravel\Jobs\Examples\Jobsets\BaseJobset $jobset
     */
    public function testExecute($jobset)
    {
        $this->assertFalse($jobset->isFailed());
        $this->assertFalse($jobset->isFinished());
        $this->assertFalse($jobset->isDispatched());
        $this->assertFalse($jobset->isOngoing());

        $jobNames = $jobset->getJobNames();
        $this->assertEquals($jobNames, [
            'visit_Beijing',
            'visit_Shanghai',
            'visit_Nanjing',
            'visit_Huangshan',
        ]);

        for ($i = 0; $i < 10; $i++) {
            $jobset->execute();
            $executionStatus = $jobset->getJobsetExecutionStatus();

            // execution status must equals jobset status
            $this->assertEquals($jobset->getStatus(), $executionStatus);

            $default = $executionStatus === JobsConst::JOB_SET_STATUS_DEFAULT;
            $ongoing = $executionStatus === JobsConst::JOB_SET_STATUS_ONGOING;
            $finished = $executionStatus === JobsConst::JOB_SET_STATUS_FINISHED;
            $failed = $executionStatus === JobsConst::JOB_SET_STATUS_FAILED;
            $this->assertTrue($default || $ongoing || $finished || $failed);
            $sum = (int)$default + (int)$ongoing + (int)$finished + (int)$failed;
            $this->assertEquals($sum, 1);

            $bjJob = $jobset->getJob('visit_Beijing');
            $shJob = $jobset->getJob('visit_Shanghai');
            $njJob = $jobset->getJob('visit_Nanjing');
            $hsJob = $jobset->getJob('visit_Huangshan');

            if ($jobset->isFinished()) {
                foreach ($jobNames as $jobName) {
                    $job = $jobset->getJob($jobName);
                    $this->assertTrue($job->isFinished());
                }
            } elseif ($jobset->isFailed()) {
                if ($bjJob->isFailed()) {
                    $this->assertEquals($shJob->getStatus(), JobsConst::JOB_SET_STATUS_DEFAULT);
                } else {
                    if ($bjJob->isFinished()) {
                        $this->assertNotEquals($shJob->getStatus(), JobsConst::JOB_SET_STATUS_DEFAULT);
                    } else {
                        $this->assertEquals($shJob->getStatus(), JobsConst::JOB_SET_STATUS_DEFAULT);
                    }
                    $this->assertTrue($shJob->isFailed() || $njJob->isFailed() || $hsJob->isFailed());
                }
            } else {
                $this->assertFalse($bjJob->isFailed());
                $this->assertFalse($shJob->isFailed());
                $this->assertFalse($njJob->isFailed());
                $this->assertFalse($hsJob->isFailed());
                $this->assertTrue(
                    (
                        $bjJob->isWaitingFeedback()
                        || $shJob->isWaitingFeedback()
                        || $njJob->isWaitingFeedback()
                        || $hsJob->isWaitingFeedback()
                    ) || (
                        $bjJob->isWaitingRetry()
                        || $shJob->isWaitingRetry()
                        || $njJob->isWaitingRetry()
                        || $hsJob->isWaitingRetry()
                    ));
                if ($bjJob->isWaitingFeedback() || $bjJob->isWaitingRetry()) {
                    $this->assertEquals($shJob->getStatus(), JobsConst::JOB_STATUS_DEFAULT);
                }
            }

            sleep(rand(1,5));
        }

        $this->assertFalse($jobset->isDispatched());

        $jobsetStatus = $jobset->getStatus();

        $this->assertTrue($jobset->execute());
        $jobset->dispatched();
        $this->assertFalse($jobset->execute());

        if ($jobsetStatus === JobsConst::JOB_SET_STATUS_FINISHED) {
            $this->assertEquals($jobset->getStatus(), JobsConst::JOB_SET_STATUS_FINISHED_AND_DISPATCHED);
        } else {
            $this->assertEquals($jobset->getStatus(), JobsConst::JOB_SET_STATUS_FAILED_AND_DISPATCHED);
        }
    }

    /**
     * @depends testExecute
     */
    public function testExecuteTime()
    {
        $jobset = NoticeJobset::make();
        $jobsetId = $jobset->getId();
        $jobset = NoticeJobset::get($jobsetId);

        for ($i = 0; $i < 10; $i ++) {
            $jobset->execute();
            if ($jobset->isOngoing()) {
                $jobOne = $jobset->getJob('notice_one');
                $jobTwo = $jobset->getJob('notice_two');
                $tryAt = date('Y-m-d H:i:s');
                if (!$jobOne->isFinished() && Helper::datetimeLE($jobOne->getTryAt(), $tryAt)) {
                    $tryAt = $jobOne->getTryAt();
                }
                if (!$jobTwo->isFinished() && Helper::datetimeLE($jobTwo->getTryAt(), $tryAt)) {
                    $tryAt = $jobTwo->getTryAt();
                }
                $this->assertEquals($jobset->getTryAt(), $tryAt);
                if (Helper::datetimeLT(date('Y-m-d H:i:s'), $jobset->getTryAt())) {
                    $this->assertFalse($jobset->execute());
                }

                sleep(rand(1,5));
            }
        }
    }

    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../bootstrap/app.php';
        $app->make(\App\Console\Kernel::class)->bootstrap();

        return $app;
    }
    public function setUp()
    {
        parent::setUp();
        $config = require __DIR__ . '/../config/jobs.php';
        $this->app['config']->set('database.default', 'mysql');
        $this->app['config']->set('database.connections.mysql.host', env('MYSQL_HOST', 'localhost'));
        $this->app['config']->set('database.connections.mysql.database', 'nanjing');
        $this->app['config']->set('database.connections.mysql.username', 'lim');
        $this->app['config']->set('database.connections.mysql.password', '123456');
        $this->app['config']->set('jobs', $config);
    }

    public function tearDown()
    {
        \Limen\Laravel\Jobs\Models\JobModel::query()->delete();
        \Limen\Laravel\Jobs\Models\JobsetModel::query()->delete();
        DB::select("delete from `migrations` where `migration` = '2018_03_19_20000_create_jobset_table'");
        DB::select("delete from `migrations` where `migration` = '2018_03_19_10000_create_job_table'");
        parent::tearDown();
    }
}