<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\QrCode\Tests;

use Endroid\QrCode\Factory\QrCodeFactory;
use Endroid\QrCode\QrCode;
use PHPUnit\Framework\TestCase;

class QrCodeTest extends TestCase
{
    public function testReadable(): void
    {
        $messages = [
            'Tiny',
            'This one has spaces',
            'd2llMS9uU01BVmlvalM2YU9BUFBPTTdQMmJabHpqdndt',
            'http://this.is.an/url?with=query&string=attached',
            '11111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111111',
            '{"i":"serialized.data","v":1,"t":1,"d":"4AEPc9XuIQ0OjsZoSRWp9DRWlN6UyDvuMlyOYy8XjOw="}',
            'Spëci&al ch@ract3rs',
            '有限公司',
        ];

        foreach ($messages as $message) {
            $qrCode = new QrCode($message);
            $qrCode->setSize(300);
            $qrCode->setValidateResult(true);
            $pngData = $qrCode->writeString();
            $this->assertTrue(is_string($pngData));
        }
    }

    public function testFactory(): void
    {
        $qrCodeFactory = new QrCodeFactory();
        $qrCode = $qrCodeFactory->create('QR Code', [
            'writer' => 'png',
            'size' => 300,
            'margin' => 10,
        ]);

        $pngData = $qrCode->writeString();
        $this->assertTrue(is_string($pngData));
    }

    public function testWriteQrCode(): void
    {
        $qrCode = new QrCode('QrCode');

        $qrCode->setWriterByName('binary');
        $binData = $qrCode->writeString();
        $this->assertTrue(is_string($binData));

        $qrCode->setWriterByName('debug');
        $debugData = $qrCode->writeString();
        $this->assertTrue(is_string($debugData));

        $qrCode->setWriterByName('eps');
        $epsData = $qrCode->writeString();
        $this->assertTrue(is_string($epsData));

        $qrCode->setWriterByName('png');
        $pngData = $qrCode->writeString();
        $this->assertTrue(is_string($pngData));
        $pngDataUriData = $qrCode->writeDataUri();
        $this->assertTrue(0 === strpos($pngDataUriData, 'data:image/png;base64'));

        $qrCode->setWriterByName('svg');
        $svgData = $qrCode->writeString();
        $this->assertTrue(is_string($svgData));
        $svgDataUriData = $qrCode->writeDataUri();
        $this->assertTrue(0 === strpos($svgDataUriData, 'data:image/svg+xml;base64'));
    }

    public function testSetSize(): void
    {
        $size = 400;
        $margin = 10;

        $qrCode = new QrCode('QrCode');
        $qrCode->setSize($size);
        $qrCode->setMargin($margin);

        $pngData = $qrCode->writeString();
        $image = imagecreatefromstring($pngData);

        $this->assertTrue(imagesx($image) === $size + 2 * $margin);
        $this->assertTrue(imagesy($image) === $size + 2 * $margin);
    }

    public function testSetLabel(): void
    {
        $qrCode = new QrCode('QrCode');
        $qrCode->setSize(300);
        $qrCode->setLabel('Scan the code', 15);

        $pngData = $qrCode->writeString();
        $this->assertTrue(is_string($pngData));
    }

    public function testSetLogo(): void
    {
        $qrCode = new QrCode('QrCode');
        $qrCode->setSize(400);
        $qrCode->setLogoPath(__DIR__.'/../assets/images/symfony.png');
        $qrCode->setLogoWidth(150);
        $qrCode->setValidateResult(true);

        $pngData = $qrCode->writeString();
        $this->assertTrue(is_string($pngData));
    }

    public function testWriteFile(): void
    {
        $filename = __DIR__.'/output/qr-code.png';

        $qrCode = new QrCode('QrCode');
        $qrCode->writeFile($filename);

        $image = imagecreatefromstring(file_get_contents($filename));

        $this->assertTrue(is_resource($image));
    }
}
