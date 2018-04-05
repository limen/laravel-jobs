<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Models;

use Limen\Jobs\Contracts\JobModelInterface;

/**
 * Class JobModel
 * @package Limen\Laravel\Jobs\Models
 *
 * @property int id
 * @property string name
 *
 */
class JobModel extends Model implements JobModelInterface
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $table = config('jobs.db_tables.job_table');
        $this->setTable($table);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTriedCount()
    {
        return $this->tried_count;
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        return $this->persist();
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getTryAt()
    {
        return $this->try_at;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getJobSetId()
    {
        return $this->jobset_id;
    }

    public function setJobsetId($jobSetId)
    {
        $this->jobset_id = $jobSetId;
    }

    public function setTryAt($tryAt)
    {
        $this->try_at = $tryAt;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setTriedCount($count)
    {
        $this->tried_count = $count;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function persist()
    {
        return $this->save();
    }

    public static function findByJobsetIdAndJobName($jobsetId, $jobName)
    {
        return static::query()
            ->where('jobset_id', $jobsetId)
            ->where('name', $jobName)
            ->first();
    }

    public static function findById($jobId)
    {
        return static::find($jobId);
    }
}