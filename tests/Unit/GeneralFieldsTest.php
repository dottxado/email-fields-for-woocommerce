<?php

declare(strict_types=1);

namespace Dottxado\EmailFieldsForWoocommerce\Tests\Unit;

use Dottxado\EmailFieldsForWoocommerce\GeneralFields;
use Dottxado\EmailFieldsForWoocommerce\Tests\TestCase;

use function Brain\Monkey\Functions\stubTranslationFunctions;
use function Brain\Monkey\Functions\when;

class GeneralFieldsTest extends TestCase
{
    public function testAddedHooks()
    {
        new GeneralFields();
        self::assertNotFalse(
            has_filter(
                'woocommerce_email_settings',
                'Dottxado\EmailFieldsForWoocommerce\GeneralFields->addReplyToSettings()'
            )
        );
        self::assertNotFalse(
            has_filter(
                'woocommerce_email_settings',
                'Dottxado\EmailFieldsForWoocommerce\GeneralFields->addBccSettings()'
            )
        );
        self::assertNotFalse(
            has_filter(
                'woocommerce_email_headers',
                'Dottxado\EmailFieldsForWoocommerce\GeneralFields->addReplyToHeader()'
            )
        );
        self::assertNotFalse(
            has_filter(
                'woocommerce_email_headers',
                'Dottxado\EmailFieldsForWoocommerce\GeneralFields->addBccHeader()'
            )
        );
    }

    public function testAddedReplyToSettings()
    {
        stubTranslationFunctions();
        $generalFields = new GeneralFields();
        $newConfigurations = $generalFields->addReplyToSettings([]);
        self::assertCount(4, $newConfigurations);
        self::assertCount(4, $newConfigurations[0]);
        self::assertCount(8, $newConfigurations[1]);
        self::assertCount(9, $newConfigurations[2]);
        self::assertCount(2, $newConfigurations[3]);
        self::assertEquals('Email Reply-To options', $newConfigurations[0]['title']);
        self::assertEquals('"Reply-To" name', $newConfigurations[1]['title']);
        self::assertEquals('"Reply-To" address', $newConfigurations[2]['title']);
        self::assertEquals('effw_reply_to_email_options', $newConfigurations[3]['id']);
    }

    public function testAddedBccSettings()
    {
        stubTranslationFunctions();
        $generalFields = new GeneralFields();
        $newConfigurations = $generalFields->addBccSettings([]);
        self::assertCount(3, $newConfigurations);
        self::assertCount(4, $newConfigurations[0]);
        self::assertCount(9, $newConfigurations[1]);
        self::assertCount(2, $newConfigurations[2]);
        self::assertEquals('Email Bcc options', $newConfigurations[0]['title']);
        self::assertEquals('"Bcc" address', $newConfigurations[1]['title']);
        self::assertEquals('effw_bcc_email_options', $newConfigurations[2]['id']);
    }

    public function testAddedReplyToHeader()
    {
        when('wp_specialchars_decode')->returnArg();
        when('get_option')->alias(
            function (string $arg): string {
                if ('effw_reply_to_name' === $arg) {
                    return 'Test name';
                } elseif ('effw_reply_to_address' === $arg) {
                    return 'test@email.com';
                }

                return '';
            }
        );
        when('sanitize_email')->returnArg();
        when('is_email')->justReturn(true);
        $generalFields = new GeneralFields();
        $headers = $generalFields->addReplyToHeader('');
        self::assertStringContainsString('Test name', $headers);
        self::assertStringContainsString('test@email.com', $headers);
        self::assertEquals("Reply-to: Test name <test@email.com>\r\n", $headers);

        when('get_option')->alias(
            function (string $arg): ?string {
                if ('effw_reply_to_name' === $arg) {
                    return null;
                } elseif ('effw_reply_to_address' === $arg) {
                    return 'test@email.com';
                }

                return '';
            }
        );
        $headersNull = $generalFields->addReplyToHeader('');
        self::assertEquals('', $headersNull);
    }
}
