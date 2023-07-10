<?php

declare(strict_types=1);

namespace Waxer\Rasterix\Matrices;

use Waxer\Rasterix\Matrices\Matrix;
use Waxer\Rasterix\Enums\MatrixType;

class Inverse implements IMatrix
{
    public function __construct(
        private Matrix $mat,
    ) {}

    /**
     * @return array<array<int>>
     */
    public function __invoke(): array
    {
        $identity = (new Matrix(MatrixType::Identity))->getMatrix();
        $matrix = $this->mat->getMatrix();

        $n = 4;

        for ($i = 0; $i < $n; $i++) {
            $matrix[$i] = array_merge($matrix[$i], $identity[$i]);
        }

        // Appliquer la méthode de Gauss-Jordan pour obtenir l'inverse
        for ($i = 0; $i < $n; $i++) {
            // Échanger les lignes si le pivot est nul
            if ($matrix[$i][$i] == 0) {
                for ($j = $i + 1; $j < $n; $j++) {
                    if ($matrix[$j][$i] != 0) {
                        $temp = $matrix[$i];
                        $matrix[$i] = $matrix[$j];
                        $matrix[$j] = $temp;
                        break;
                    }
                }
            }

            // Diviser la ligne par le pivot
            $pivot = $matrix[$i][$i];
            for ($j = 0; $j < 2 * $n; $j++) {
                $matrix[$i][$j] /= $pivot;
            }

            // Éliminer les autres éléments de la colonne du pivot
            for ($j = 0; $j < $n; $j++) {
                if ($j !== $i) {
                    $factor = $matrix[$j][$i];
                    for ($k = 0; $k < 2 * $n; $k++) {
                        $matrix[$j][$k] -= $factor * $matrix[$i][$k];
                    }
                }
            }
        }

        // Extraire la matrice inverse
        $inverse = [];
        for ($i = 0; $i < $n; $i++) {
            $inverse[$i] = array_slice($matrix[$i], $n);
        }


        return $inverse;
    }
}
