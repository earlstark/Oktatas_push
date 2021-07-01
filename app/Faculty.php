<?php

namespace app;

class Faculty
{
    private $data = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function isStudentMatch(Student $student): bool {
        list($egyetem, $kar, $szak) = $student->getFacultyData();
        if ( ($this->data["egyetem"] == $egyetem) && ($this->data["kar"] == $kar) && ($this->data["szak"] == $szak) ) {
            return true;
        }
        return false;
    }

    public function getRequiredSubjects() {
        return [
            $this->data["kotolezo-erettsegi-targy"],
            $this->data["kotolezoen-vallaszthato-erettsegi-targyak"],
        ];
    }
}