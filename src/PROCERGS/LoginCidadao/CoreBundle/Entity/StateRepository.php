<?php

namespace PROCERGS\LoginCidadao\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class StateRepository extends EntityRepository
{

    public function findByString($string)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb
                ->select('s')
                ->from('PROCERGSLoginCidadaoCoreBundle:State', 's')
                ->join('PROCERGSLoginCidadaoCoreBundle:Country', 'c', 'WITH',
                       's.country = c')
                ->where('s.name LIKE :string OR LOWER(s.name) LIKE :string')
                ->addOrderBy('c.preference', 'DESC')
                ->addOrderBy('s.name', 'ASC')
                ->setParameter('string', "$string%")
                ->getQuery()->getResult();
    }

    public function findOneByString($string)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('s')
            ->from('PROCERGSLoginCidadaoCoreBundle:State', 's')
            ->orWhere('s.name = :string')
            ->orWhere('s.acronym = :string')
            ->orWhere('s.iso6 = :string')
            ->setParameter('string', $string);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findPreferred()
    {
        return $this->createQueryBuilder('s')
                ->where('s.preference > 0')
                ->addOrderBy('s.preference', 'DESC')
                ->getQuery()->getResult();
    }

}
