<?php
/**
 * PreferencesControllerTest.php
 * Copyright (c) 2018 thegrumpydictator@gmail.com
 *
 * This file is part of Firefly III.
 *
 * Firefly III is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Firefly III is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Firefly III. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Tests\Api\V1\Controllers;

use FireflyIII\Models\Preference;
use FireflyIII\Transformers\PreferenceTransformer;
use Laravel\Passport\Passport;
use Log;
use Mockery;
use Preferences;
use Tests\TestCase;

/**
 *
 * Class PreferencesControllerTest
 */
class PreferencesControllerTest extends TestCase
{

    /**
     * Set up test
     */
    public function setUp(): void
    {
        parent::setUp();
        Passport::actingAs($this->user());
        Log::info(sprintf('Now in %s.', \get_class($this)));
    }

    /**
     * @covers \FireflyIII\Api\V1\Controllers\PreferenceController
     */
    public function testIndex(): void
    {
        $transformer = $this->mock(PreferenceTransformer::class);
        $available   = ['language', 'customFiscalYear', 'fiscalYearStart', 'currencyPreference', 'transaction_journal_optional_fields', 'frontPageAccounts',
                        'viewRange', 'listPageSize, twoFactorAuthEnabled',];

        foreach ($available as $pref) {
            Preferences::shouldReceive('getForUser')->withArgs([Mockery::any(), $pref])->once();
        }

        // mock calls to transformer:
        $transformer->shouldReceive('setParameters')->withAnyArgs()->atLeast()->once();



        // call API
        $response = $this->get('/api/v1/preferences');
        $response->assertStatus(200);
    }

    /**
     * @covers \FireflyIII\Api\V1\Controllers\PreferenceController
     * @covers \FireflyIII\Api\V1\Requests\PreferenceRequest
     */
    public function testUpdateArray(): void
    {
        $transformer = $this->mock(PreferenceTransformer::class);

        // mock calls to transformer:
        $transformer->shouldReceive('setParameters')->withAnyArgs()->atLeast()->once();
        $transformer->shouldReceive('setCurrentScope')->withAnyArgs()->atLeast()->once()->andReturnSelf();
        $transformer->shouldReceive('getDefaultIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('getAvailableIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('transform')->atLeast()->once()->andReturn(['id' => 5]);

        /** @var Preference $preference */
        $preference = Preferences::setForUser($this->user(), 'frontPageAccounts', [1, 2, 3]);
        $data       = ['data' => '4,5,6'];
        $response   = $this->put('/api/v1/preferences/' . $preference->name, $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

    }

    /**
     * @covers \FireflyIII\Api\V1\Controllers\PreferenceController
     * @covers \FireflyIII\Api\V1\Requests\PreferenceRequest
     */
    public function testUpdateBoolean(): void
    {
        $transformer = $this->mock(PreferenceTransformer::class);

        // mock calls to transformer:
        $transformer->shouldReceive('setParameters')->withAnyArgs()->atLeast()->once();
        $transformer->shouldReceive('setCurrentScope')->withAnyArgs()->atLeast()->once()->andReturnSelf();
        $transformer->shouldReceive('getDefaultIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('getAvailableIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('transform')->atLeast()->once()->andReturn(['id' => 5]);

        /** @var Preference $preference */
        $preference = Preferences::setForUser($this->user(), 'twoFactorAuthEnabled', false);
        $data       = ['data' => '1'];
        $response   = $this->put('/api/v1/preferences/' . $preference->name, $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

    }

    /**
     * @covers \FireflyIII\Api\V1\Controllers\PreferenceController
     * @covers \FireflyIII\Api\V1\Requests\PreferenceRequest
     */
    public function testUpdateDefault(): void
    {
        $transformer = $this->mock(PreferenceTransformer::class);

        // mock calls to transformer:
        $transformer->shouldReceive('setParameters')->withAnyArgs()->atLeast()->once();
        $transformer->shouldReceive('setCurrentScope')->withAnyArgs()->atLeast()->once()->andReturnSelf();
        $transformer->shouldReceive('getDefaultIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('getAvailableIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('transform')->atLeast()->once()->andReturn(['id' => 5]);

        /** @var Preference $preference */
        $preference = Preferences::setForUser($this->user(), 'currencyPreference', false);
        $data       = ['data' => 'EUR'];
        $response   = $this->put('/api/v1/preferences/' . $preference->name, $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

    }

    /**
     * @covers \FireflyIII\Api\V1\Controllers\PreferenceController
     * @covers \FireflyIII\Api\V1\Requests\PreferenceRequest
     */
    public function testUpdateInteger(): void
    {
        $transformer = $this->mock(PreferenceTransformer::class);

        // mock calls to transformer:
        $transformer->shouldReceive('setParameters')->withAnyArgs()->atLeast()->once();
        $transformer->shouldReceive('setCurrentScope')->withAnyArgs()->atLeast()->once()->andReturnSelf();
        $transformer->shouldReceive('getDefaultIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('getAvailableIncludes')->withAnyArgs()->atLeast()->once()->andReturn([]);
        $transformer->shouldReceive('transform')->atLeast()->once()->andReturn(['id' => 5]);

        /** @var Preference $preference */
        $preference = Preferences::setForUser($this->user(), 'listPageSize', 13);
        $data       = ['data' => '434'];
        $response   = $this->put('/api/v1/preferences/' . $preference->name, $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

    }

}