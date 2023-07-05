<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarsRoversController extends Controller
{
    public function process(Request $request)
    {
        $inputData = $request->input('input_data');

        $lines = explode("\n", $inputData);

        $plateauCoordinates = explode(' ', $lines[0]);
        $plateauWidth = intval($plateauCoordinates[0]);
        $plateauHeight = intval($plateauCoordinates[1]);

        $results = [];
        $roverData = [];
        $roverIndex = 0;

        for ($i = 1; $i < count($lines); $i++) {
            if ($i % 2 !== 0) {
                $position = explode(' ', $lines[$i]);
                $x = intval($position[0]);
                $y = intval($position[1]);
                $direction = $position[2];

                $roverData[$roverIndex] = [
                    'x' => $x,
                    'y' => $y,
                    'direction' => $direction
                ];
            } else {
                $instructions = str_split($lines[$i]);

                foreach ($instructions as $instruction) {
                    switch ($instruction) {
                        case 'L':
                            $this->turnLeft($roverData[$roverIndex]['direction']);
                            break;
                        case 'R':
                            $this->turnRight($roverData[$roverIndex]['direction']);
                            break;
                        case 'M':
                            $this->moveForward($roverData[$roverIndex], $plateauWidth, $plateauHeight);
                            break;
                    }
                }

                $results[$roverIndex] = $roverData[$roverIndex];
                $roverIndex++;
            }
        }

        return response()->json($results);
    }

    private function turnLeft(&$direction)
    {
        switch ($direction) {
            case 'N':
                $direction = 'W';
                break;
            case 'E':
                $direction = 'N';
                break;
            case 'S':
                $direction = 'E';
                break;
            case 'W':
                $direction = 'S';
                break;
        }
    }

    private function turnRight(&$direction)
    {
        switch ($direction) {
            case 'N':
                $direction = 'E';
                break;
            case 'E':
                $direction = 'S';
                break;
            case 'S':
                $direction = 'W';
                break;
            case 'W':
                $direction = 'N';
                break;
        }
    }

    private function moveForward(&$rover, $plateauWidth, $plateauHeight)
    {
        switch ($rover['direction']) {
            case 'N':
                if ($rover['y'] < $plateauHeight) {
                    $rover['y']++;
                }
                break;
            case 'E':
                if ($rover['x'] < $plateauWidth) {
                    $rover['x']++;
                }
                break;
            case 'S':
                if ($rover['y'] > 0) {
                    $rover['y']--;
                }
                break;
            case 'W':
                if ($rover['x'] > 0) {
                    $rover['x']--;
                }
                break;
        }
    }
}
