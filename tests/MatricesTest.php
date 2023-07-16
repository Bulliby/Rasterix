<?php 

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Waxer\Rasterix\Enums\MatrixType;
use Waxer\Rasterix\Matrices\Matrix;
use Waxer\Rasterix\Vector;
use Waxer\Rasterix\Vertex;

require_once './vendor/autoload.php';

final class MatricesTest extends TestCase
{
    public function testIdentity(): void
    {
        $identity = new Matrix(MatrixType::Identity);

        $this->assertSame([
            [1, 0, 0, 0],
            [0, 1, 0, 0],
            [0, 0, 1, 0],
            [0, 0, 0, 1],
        ], $identity->getMatrix());
    }

    public function testScale(): void
    {
        $scale = 20.0;
        $matrixScale = new Matrix(MatrixType::Scale, $scale);

        $this->assertSame([
            [$scale, 0, 0, 0],
            [0, $scale, 0, 0],
            [0, 0, $scale, 0],
            [0, 0, 0, 1],
        ], $matrixScale->getMatrix());
    }

    public function testTranslation(): void
    {
        $x = 10.0; $y = 15.0; $z = 20.0;
        $vertex = new Vertex(['x' => $x, 'y' => $y, 'z' => $z]);
        $vector = new Vector(['dest' => $vertex]);
        $translation = new Matrix(MatrixType::Translation, $vector);

        $this->assertSame([
            [1, 0, 0, 0],
            [0, 1, 0, 0],
            [0, 0, 1, 0],
            [$x, $y, $z, 1],
        ], $translation->getMatrix());
    }

    public function testRx(): void
    {
        $angle = M_PI_4;
        $rx = new Matrix(MatrixType::RX, $angle);

        $this->assertSame([
            [1, 0, 0, 0],
            [0, cos($angle), sin($angle), 0],
            [0, -sin($angle), cos($angle), 0],
            [0, 0, 0, 1],
        ], $rx->getMatrix());
    }

    public function testRy(): void
    {
        $angle = M_PI_4;
        $ry = new Matrix(MatrixType::RY, $angle);

        $this->assertSame([
            [cos($angle), 0, -sin($angle), 0],
            [0, 1, 0, 0],
            [sin($angle), 0, cos($angle), 0],
            [0, 0, 0, 1],
        ], $ry->getMatrix());
    }

    public function testRz(): void
    {
        $angle = M_PI_4;
        $rz = new Matrix(MatrixType::RZ, $angle);

        $this->assertSame([
            [cos($angle), sin($angle), 0, 0],
            [-sin($angle), cos($angle), 0, 0],
            [0, 0, 1, 0],
            [0, 0, 0, 1],
        ], $rz->getMatrix());
    }
}
