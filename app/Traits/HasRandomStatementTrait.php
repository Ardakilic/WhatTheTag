<?php namespace App\Traits;

/**
 * Class HasRandomStatementTrait
 * Source: https://gist.github.com/vluzrmos/8046e20769a595a4b0d0
 * Discussion: https://github.com/laravel/framework/pull/5435
 */

trait HasRandomStatementTrait
{
    /**
     * Random Statements by driver name.
     * @var array
     */
    protected $randomStatements = [
        'mysql' => 'RAND()',
        'pgsql' => 'RANDOM()',
        'sqlite' => 'RANDOM()',
        'sqlsrv' => 'NEWID()',
    ];

    /**
     * Default random statement.
     *
     * @var string
     */
    protected $defaultRandomStatement = 'RANDOM()';

    /**
     * Scope to order by random statement.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @param  string $driver
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeOrderByRandom($query, $driver = null)
    {
        return $query->orderByRaw($this->getRandomStatement($driver));
    }

    /**
     * Get random statement for a given driver name.
     *
     * @param  string $driver
     * @return string
     */
    public function getRandomStatement($driver = null)
    {
        $driver = $driver ?: $this->getCurrentDriverName();

        if (isset($this->randomStatements[$driver])) {
            return $this->randomStatements[$driver];
        }

        return $this->defaultRandomStatement;
    }

    /**
     * Get the driver name for the current connection.
     *
     * @return string
     */
    public function getCurrentDriverName()
    {
        return $this->getConnection()->getDriverName();
    }
}