<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Models\User\Settings;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Infrastructure\Models\User;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @var \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    private $repository;

    public function save(User $user): void
    {
        $this->persist($user);
        $this->flush();
    }

    /**
     * @param string $telegramUserId
     * @return User|null
     */
    public function findByTelegramUserId(string $telegramUserId)
    {
        return $this->_getRepository()->findOneBy(['telegram_user_id' => $telegramUserId]);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id)
    {
        return $this->_getRepository()->find($id);
    }

    public function getAll(): array
    {
        $settings = new Settings();
        $settings->setSpots(['0190', '0175', 'egypt_1']);
//        $settings->setSpots([ 'egypt_1']);
//        $settings->setReportFormat('settings#report_format:knots');
        $settings->setNotificationsSettings('settings#notifications:custom');
        $settings->setNotificationParameter('settings#notifications_parameter:Avg');
        $settings->setCustomNotificationsSettings('>= 6 knots');
        return [new \App\Core\Domain\Models\User('12345', $settings)];
    }

    /**
     * @return \Doctrine\ORM\EntityRepository|\Doctrine\Persistence\ObjectRepository
     */
    private function _getRepository()
    {
        if ($this->repository !== null) {
            return $this->repository;
        }

        $this->repository = parent::getRepository('Anemometer\Infrastructure\Models\User');
        return $this->repository;
    }
}
