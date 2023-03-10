<?php

namespace App\Core\Infrastructure\Service;

use App\Core\Domain\Models\User;
use App\Core\Domain\Models\User\Settings;
use App\Core\Domain\Service\UserServiceInterface;
use App\Core\Infrastructure\Repository\UserRepository;

class UserService implements UserServiceInterface
{

    /**
     * @var array<string,User>
     */
    private array $users = [];

    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function initUser(string $telegramUserId): User
    {
        $loadedUser = $this->users[$telegramUserId] ?? null;
        if ($loadedUser !== null) {
            return $loadedUser;
        }
        $user = $this->userRepository->findByTelegramUserId($telegramUserId);
        if ($user === null) {
            $user = new \Anemometer\Infrastructure\Models\User($telegramUserId);
            $this->userRepository->save($user);
        }

        $this->users[$telegramUserId] = $this->convertMapperToModel($user);

        return $this->users[$telegramUserId];
    }

    public function saveUser(User $user): void
    {
        $this->users[$user->getId()] = $user;
        $userMapper = $this->userRepository->findByTelegramUserId($user->getId());
        if ($userMapper !== null) {
            $settings = json_decode(json_encode($user->getSettings()), true);
            $userMapper->setSettings($settings);
            $this->userRepository->save($userMapper);
        }
    }

    /**
     * @return \App\Core\Domain\Models\User[]
     */
    public function getAll(): array
    {
        $models = $this->userRepository->getAll();
        foreach ($models as $model) {
            $this->users[$model->getTelegramUserId()] = $this->convertMapperToModel($model);
        }

        return $this->users;
    }

    private function convertMapperToModel(\Anemometer\Infrastructure\Models\User $user): User
    {
        $settings = Settings::fromData($user->getSettings());
        return new User($user->getTelegramUserId(), $settings);
    }
}
