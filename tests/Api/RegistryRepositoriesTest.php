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

namespace Gitlab\Tests\Api;

use Gitlab\Api\RegistryRepositories;

class RegistryRepositoriesTest extends TestCase
{
    protected function getApiClass()
    {
        return RegistryRepositories::class;
    }

    /**
     * @test
     */
    public function shouldChangeContainerRegistryVisibility(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('put')
            ->with('projects/1/', ['container_registry_access_level' => 'private'])
        ;

        $api->changeContainerRegistryVisibility(1, ['container_registry_access_level' => 'private']);
    }

    /**
     * @test
     */
    public function shouldGetAllRegistryRepositories(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('projects/1/registry/repositories')
        ;

        $api->all(1);
    }

    /**
     * @test
     */
    public function shouldGetAllRegistryRepositoriesWithParams(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('projects/1/registry/repositories', ['tags' => true, 'tags_count' => true])
        ;

        $api->all(1, ['tags' => true, 'tags_count' => true]);
    }

    /**
     * @test
     */
    public function shouldGetRegistryRepository(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('registry/repositories/1')
        ;

        $api->show(1);
    }

    /**
     * @test
     */
    public function shouldGetRegistryRepositoryWithParams(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('registry/repositories/1', ['tags' => true, 'tags_count' => true, 'size' => true])
        ;

        $api->show(1, ['tags' => true, 'tags_count' => true, 'size' => true]);
    }

    /**
     * @test
     */
    public function shouldRemoveRegistryRepository(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('projects/1/registry/repositories/2')
        ;

        $api->remove(1, 2);
    }

    /**
     * @test
     */
    public function shouldGetAllRegistryRepositoryTags(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('projects/1/registry/repositories/2/tags')
        ;

        $api->tags(1, 2);
    }

    /**
     * @test
     */
    public function shouldGetRegistryRepositoryTag(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('get')
            ->with('projects/1/registry/repositories/2/tags/foo')
        ;

        $api->tag(1, 2, 'foo');
    }

    /**
     * @test
     */
    public function shouldRemoveRegistryRepositoryTag(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('projects/1/registry/repositories/2/tags/foo')
        ;

        $api->removeTag(1, 2, 'foo');
    }

    /**
     * @test
     */
    public function shouldRemoveRegistryRepositoryTags(): void
    {
        $api = $this->getApiMock();
        $api->expects($this->once())
            ->method('delete')
            ->with('projects/1/registry/repositories/2/tags', ['name_regex_delete' => 'test-.+', 'name_regex_keep' => 'test-foo', 'keep_n' => 1, 'older_than' => '1month'])
        ;

        $api->removeTags(1, 2, ['name_regex_delete' => 'test-.+', 'name_regex_keep' => 'test-foo', 'keep_n' => 1, 'older_than' => '1month']);
    }
}
