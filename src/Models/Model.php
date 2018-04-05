<?php
/**
 * Author: LI Mengxiang
 * Email: limengxiang876@gmail.com
 * Date: 2018/3/19
 */

namespace Limen\Laravel\Jobs\Models;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $conn = config('jobs.db_connection', 'job') ?: config('database.default');
        $this->setConnection($conn);
    }
}