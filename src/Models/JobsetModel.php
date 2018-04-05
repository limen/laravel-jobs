<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Models;

use Limen\Jobs\Contracts\JobsetModelInterface;

class JobsetModel extends Model implements JobsetModelInterface
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $table = config('jobs.db_tables.jobset_table');
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

    public function getJobIds()
    {
        $rows = $this->hasMany(JobModel::class, 'jobset_id', 'id')
            ->select('id')
            ->get()
            ->toArray();

        return $rows ? array_column($rows, 'id') : [];
    }

    public function setId($id)
    {
        $this->id = $id;
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
        //
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function persist()
    {
        return $this->save();
    }

    public static function findById($id)
    {
        return static::find($id);
    }
}