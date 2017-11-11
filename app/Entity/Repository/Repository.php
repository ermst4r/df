<?php

namespace App\Entity\Repository;

use Cascade\Cascade;
use Illuminate\Database\QueryException;
use Monolog\Logger;

/**
 * Base Repository.
 */
class Repository
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Repository constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Returns the first record in the database.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * Returns all the records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Returns the count of all the records.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Returns a range of records bounded by pagination parameters.
     *
     * @param int $limit
     * @param int $offset
     *
     * @param array $relations
     * @param string $orderBy
     * @param string $sorting
     * @return \Illuminate\Database\Eloquent\Collection
     * @internal param limit $int
     */
    public function page($limit = 10, $offset = 0, array $relations = [], $orderBy = 'updated_at', $sorting = 'desc')
    {
        return $this->model->with($relations)->take($limit)->skip($offset)->orderBy($orderBy, $sorting)->get();
    }

    /**
     * Find a record by its identifier.
     *
     * @param string $id
     * @param array  $relations
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $relations = null)
    {
        return $this->findBy($this->model->getKeyName(), $id, $relations);
    }

    /**
     * Find a record by an attribute.
     * Fails if no model is found.
     *
     * @param string $attribute
     * @param string $value
     * @param array  $relations
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBy($attribute, $value, $relations = null)
    {
        $query = $this->model->where($attribute, $value);

        if ($relations && is_array($relations)) {
            foreach ($relations as $relation) {
                $query->with($relation);
            }
        }

        return $query->firstOrFail();
    }

    /**
     * Get all records by an associative array of attributes.
     * Two operators values are handled: AND | OR.
     *
     * @param array  $attributes
     * @param string $operator
     * @param array  $relations
     *
     * @return \Illuminate\Support\Collection
     */
    public function getByAttributes(array $attributes, $operator = 'AND', $relations = null)
    {

        // In the following it doesn't matter wivh element to start with,
        // in all cases all attributes will be appended to the builder.

        // Get the last value of the associative array
        $lastValue = end($attributes);

        // Get the last key of the associative array
        $lastKey = key($attributes);

        // Builder
        $query = $this->model->where($lastKey, $lastValue);

        // Pop the last key value pair of the associative array now that it has been added to Builder already
        array_pop($attributes);

        $method = 'where';

        if (strtoupper($operator) === 'OR') {
            $method = 'orWhere';
        }

        foreach ($attributes as $key => $value) {
            $query->$method($key, $value);
        }

        if ($relations && is_array($relations)) {
            foreach ($relations as $relation) {
                $query->with($relation);
            }
        }

        return $query->get();
    }

    /**
     * Create model with attributes and insert in database
     * with $attributes.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($attributes)
    {
        try {
            return $this->model->create($attributes);
        } catch (QueryException $e) {
            $this->queryLogger($e);
            return null;
        }
    }

    /**
     * Fills out an instance of the model
     * with $attributes.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function fill($attributes)
    {
        return $this->model->fill($attributes);
    }

    /**
     * Fills out an instance of the model
     * and saves it, pretty much like mass assignment.
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function fillAndSave($attributes)
    {
        try {
            $this->model->fill($attributes);
            $this->model->save();

            return $this->model;
        } catch (QueryException $e) {
            $this->queryLogger($e);
            return null;
        }
    }

    /**
     * Remove a selected record.
     *
     * @param string $key
     *
     * @return bool
     */
    public function remove($key)
    {
        try {
            return $this->model->where($this->model->getKeyName(), $key)->delete();
        } catch (QueryException $e) {
            $this->queryLogger($e);
            return false;
        }
    }

    /**
     * Implement a convenience call to findBy
     * which allows finding by an attribute name
     * as follows: findByName or findByAlias.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        /*
         * findBy convenience calling to be available
         * through findByName and findByTitle etc.
         */

        if (preg_match('/^findBy/', $method)) {
            $attribute = strtolower(substr($method, 6));
            array_unshift($arguments, $attribute);

            return call_user_func_array([$this, 'findBy'], $arguments);
        }

        parent::__call($method, $arguments);
    }

    /**
     * Log the query if there is an exception
     * @param QueryException $exception
     */
    protected function queryLogger(QueryException $exception)
    {
//        Cascade::getLogger('query_log')
//            ->addRecord(
//                Logger::INFO,
//                $exception->getMessage(),
//                [$exception]
//            );
    }
}
