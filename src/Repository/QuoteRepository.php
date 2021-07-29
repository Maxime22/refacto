<?php

namespace Repository;
use Entity\Quote;

class QuoteRepository implements RepositoryInterface
{
    /**
     * @param int $id
     *
     * @return Quote
     */
    public function getById($id)
    {
        $generator = \Faker\Factory::create();
        $generator->seed($id);
        return new Quote(
            $id,
            $generator->numberBetween(1, 10),
            $generator->numberBetween(1, 200),
            $generator->dateTime()
        );
    }
}
