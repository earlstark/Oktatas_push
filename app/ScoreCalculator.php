<?php

namespace app;

use app\Utility;
use app\Faculty;

class ScoreCalculator
{
    /**
     * @var Student[]
     */
    private $students = [];

    /**
     * @var Faculty[]
     */
    private $faculties = [];

    public function __construct() {
        $this->loadStudents(json_decode(file_get_contents(Utility::getBase()."data/students.json"), true));
        $this->loadFaculties(json_decode(file_get_contents(Utility::getBase()."data/faculties.json"), true));
        $this->addFacultiesToStudents();
    }

    public static function getInstance(): ScoreCalculator {
        return new static();
    }

    public function getStudentByIndex(int $index): Student {
        return $this->students[$index];
    }

    public function getScores(): array {
        return $this->students;
    }

    private function loadStudents(array $data) {
        foreach ($data as $d) {
            $this->students[] = new Student($d);
        }
    }

    private function loadFaculties(array $data) {
        foreach ($data as $d) {
            $this->faculties[] = new Faculty($d);
        }
    }

    private function addFacultiesToStudents() {
        foreach ($this->students as $student) {
            foreach ($this->faculties as $faculty) {
                if ($faculty->isStudentMatch($student)) {
                    $student->setFaculty($faculty);
                    continue;
                }
            }
        }
    }

}
