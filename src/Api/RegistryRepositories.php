<?php

declare(strict_types=1);

/*
 * This file is part of the Gitlab API library.
 *
 * (c) Jan-Hendrik Willms <tleilax@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gitlab\Api;

class RegistryRepositories extends AbstractApi
{
    public const ACCESS_LEVEL_ENABLED = 'enabled';
    public const ACCESS_LEVEL_PRIVATE = 'private';
    public const ACCESS_LEVEL_DISABLED = 'disabled';

    /**
     * @param string|int $project_id
     * @param array      $parameters
     *
     *      @var bool $tags       include tags
     *      @var bool $tags_count include tags count
     *
     * @return mixed
     */
    public function all($project_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('tags')
            ->setAllowedTypes('tags', ['bool']);

        $resolver->setDefined('tags_count')
            ->setAllowedTypes('tags_count', ['bool']);

        return $this->get(
            $this->getProjectPath($project_id, 'registry/repositories'),
            $resolver->resolve($parameters)
        );
    }

    /**
     * @param string|int $project_id
     * @param array      $parameters
     *
     *     @var string $container_registry_access_level access level to set (enabled, private or disabled)
     *
     * @return mixed
     */
    public function changeContainerRegistryVisibility($project_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('container_registry_access_level')
            ->setAllowedValues('container_registry_access_level', [
                self::ACCESS_LEVEL_ENABLED,
                self::ACCESS_LEVEL_PRIVATE,
                self::ACCESS_LEVEL_DISABLED,
            ]);

        return $this->put(
            $this->getProjectPath($project_id, ''),
            $resolver->resolve($parameters)
        );
    }

    /**
     * @param string|int $registry_repository_id
     * @param array      $parameters
     *
     *     @var bool $tags       include tags
     *     @var bool $tags_count include tags count
     *     @var bool $size       include size
     *
     * @return mixed
     */
    public function show($registry_repository_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('tags')
            ->setAllowedTypes('tags', ['bool']);

        $resolver->setDefined('tags_count')
            ->setAllowedTypes('tags_count', ['bool']);

        $resolver->setDefined('size')
            ->setAllowedTypes('size', ['bool']);

        return $this->get(
            'registry/repositories/'.self::encodePath($registry_repository_id),
            $resolver->resolve($parameters)
        );
    }

    /**
     * @param string|int $project_id
     * @param string|int $registry_repository_id
     *
     * @return mixed
     */
    public function remove($project_id, $registry_repository_id)
    {
        return $this->delete(
            $this->getProjectPath($project_id, 'registry/repositories/'.self::encodePath($registry_repository_id))
        );
    }

    /**
     * @param string|int $project_id
     * @param string|int $registry_repository_id
     *
     * @return mixed
     */
    public function tags($project_id, $registry_repository_id)
    {
        return $this->get(
            $this->getProjectPath($project_id, 'registry/repositories/'.self::encodePath($registry_repository_id).'/tags')
        );
    }

    /**
     * @param string|int $project_id
     * @param string|int $registry_repository_id
     * @param string $tag_name
     *
     * @return mixed
     */
    public function tag($project_id, $registry_repository_id, $tag_name)
    {
        return $this->get(
            $this->getProjectPath($project_id, 'registry/repositories/'.self::encodePath($registry_repository_id).'/tags/'.self::encodePath($tag_name))
        );
    }

    /**
     * @param string|int $project_id
     * @param string|int $registry_repository_id
     * @param string $tag_name
     *
     * @return mixed
     */
    public function removeTag($project_id, $registry_repository_id, $tag_name)
    {
        return $this->delete(
            $this->getProjectPath($project_id, 'registry/repositories/'.self::encodePath($registry_repository_id).'/tags/'.self::encodePath($tag_name))
        );
    }

    /**
     * @param string|int $project_id
     * @param string|int $registry_repository_id
     * @param array $parameters
     *
     *     @var string $name_regex_delete delete all tags matching this regex
     *     @var string $name_regex_keep   keep all tags matching this regex
     *     @var int    $keep_n            keep n latest tags for each matching tag
     *     @var string $older_than        delete tags older than the given time (written in human readable form 1h, 1d, 1month)
     *
     * @return mixed
     */
    public function removeTags($project_id, $registry_repository_id, array $parameters = [])
    {
        $resolver = $this->createOptionsResolver();

        $resolver->setDefined('name_regex_delete')
            ->setRequired('name_regex_delete')
            ->setAllowedTypes('name_regex_delete', 'string');

        $resolver->setDefined('name_regex_keep')
            ->setAllowedTypes('name_regex_keep', 'string');

        $resolver->setDefined('keep_n')
            ->setAllowedTypes('keep_n', 'int');

        $resolver->setDefined('older_than')
            ->setAllowedTypes('older_than', 'string');

        return $this->delete(
            $this->getProjectPath($project_id, 'registry/repositories/'.self::encodePath($registry_repository_id).'/tags'),
            $resolver->resolve($parameters)
        );
    }
}
