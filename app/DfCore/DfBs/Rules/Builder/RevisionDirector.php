<?php
/**
 *  This file is part of Dfbuilder.
 *
 *     Dfbuilder is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     Dfbuilder is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with Dfbuilder.  If not, see <http://www.gnu.org/licenses/>
 */


namespace App\DfCore\DfBs\Rules\Builder;


use App\Entity\Repository\RevisionRepository;
use App\Entity\Revision;

class RevisionDirector
{
    private $builder;

    public  function __construct()
    {
        $this->builder = new RevisionBuilder();

    }


    /**
     * Build the revision...
     * @param $feed_id
     * @param $rules
     * @return mixed
     */
    public function buildRevision($channel_feed_id, $rules)
    {
        $revision = new RevisionRepository(new Revision());
        $this->builder->setDeleteRevisions($revision->getDeletedRevisionData($channel_feed_id));
        $this->builder->setUpdateRevision($revision->getUpdatedRevisionData($channel_feed_id));
        $this->builder->setRules($rules);
        return $this->builder->buildRule();
    }

}