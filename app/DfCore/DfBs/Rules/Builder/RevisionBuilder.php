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

/**
 * Created by PhpStorm.
 * User: erm
 * Date: 11-05-17
 * Time: 10:43
 */

namespace App\DfCore\DfBs\Rules\Builder;


class RevisionBuilder extends AbstractRuleBuilder
{

    private $delete_revisions;
    private $update_revision;
    private $rules;

    /**
     * @return mixed
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param mixed $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }


    /**
     * @return mixed
     */
    public function getDeleteRevisions()
    {
        return $this->delete_revisions;
    }

    /**
     * @param mixed $delete_revisions
     */
    public function setDeleteRevisions($delete_revisions)
    {
        $this->delete_revisions = $delete_revisions;
    }

    /**
     * @return mixed
     */
    public function getUpdateRevision()
    {
        return $this->update_revision;
    }

    /**
     * @param mixed $update_revision
     */
    public function setUpdateRevision($update_revision)
    {
        $this->update_revision = $update_revision;
    }

    /**
     * Apply the revisions...
     * @return mixed
     */
    public function buildRule()
    {
        $rules = $this->getRules();

        /**
         * Delete the entrys in the revision..
         */
        foreach($this->getDeleteRevisions() as $ids) {
            if(isset($rules[$ids])) {
                unset($rules[$ids]);
            }
        }

        /**
         * Update the data from the revisions...
         */
       foreach ($this->getUpdateRevision() as $u) {

          if(isset($rules[$u->generated_id]['_source'][$u->revision_field_name])) {
              $rules[$u->generated_id]['_source'][$u->revision_field_name] = $u->revision_new_content;
          }
       }
        return $rules;
    }

}