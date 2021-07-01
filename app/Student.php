<?php

namespace app;

class Student
{
    private $data = [];

    /**
     * @var Faculty
     */
    private $faculty;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function setFaculty(Faculty $faculty) {
        $this->faculty = $faculty;
    }

    public function getFacultyData(): array {
        $vs = $this->data["valasztott-szak"];
        return [
            $vs["egyetem"],
            $vs["kar"],
            $vs["szak"],
        ];
    }

    public function isGraduationValid(): bool {
        foreach ($this->data["erettsegi-eredmenyek"] as $d) {
            if (intval($d["eredmeny"]) < Rules::get("minimum-erettsegi-eredmeny")) {
                return false;
            }
        }
        return true;
   }

   public function hasRequiredSubjects(): bool {
       $subjects = $this->getGraduationnames();
       foreach (Rules::get("kotolezo-erettsegi-targyak") as $d) {
           if (!in_array($d, $subjects)) {
               return false;
           }
       }
       return true;
   }

    public function isGraduationValidForFaculty(): bool {
        $subjects = $this->getGraduationnames();
        list($req, $hasOne) = $this->faculty->getRequiredSubjects();
        if (!in_array($req, $subjects)) {
            return false;
        }
        foreach ($hasOne as $h) {
            if (in_array($h, $subjects)) {
                return true;
            }
        }
        return false;
    }

    public function getScore(): array {
        if (!$this->isGraduationValid()) {
            return [
                "score" => 0,
                "error" => "hiba, nem lehetséges a pontszámítás a magyar nyelv és irodalom tárgyból elért 20% alatti eredmény miatt",
            ];
        }
        if (!$this->hasRequiredSubjects()) {
            return [
              "score" => 0,
              "error" => "hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt",
            ];
        }
        if (!$this->isGraduationValidForFaculty()) {
            return [
                "score" => 0,
                "error" => "hiba, nem lehetséges a pontszámítás a szak érettségi alapfeltételei miatt",
            ];
        }
        $baseScore = $this->getBaseScore();
        $plusScore = $this->getPlusScore();
        return [
            "score" => $baseScore + $plusScore,
            "baseScore" => $baseScore,
            "plusScore" => $plusScore,
            "error" => false,
        ];
    }

    public function getBaseScore(): int {
        list($req, $hasOne) = $this->faculty->getRequiredSubjects();
        $score = $this->getScoreForSubject($req);
        $maxScore = 0;
        foreach ($hasOne as $h) {
            $actScore = $this->getScoreForSubject($h);
            if ($actScore > $maxScore) {
                $maxScore = $actScore;
            }
        }
        $score += $maxScore;
        $score *= 2;
        return $score;
    }

    public function getPlusScore(): int {
        $score = $this->getLanguageExamScore();
        $score += $this->getAdvencedSubjectsScores();
        return ($score > 100) ? 100 : $score;
    }

    private function getAdvencedSubjectsScores(): int {
        $score = 0;
        foreach ($this->data["erettsegi-eredmenyek"] as $d) {
            if ($d["tipus"] == "emelt") {
                $score += Rules::get("emelt-szintu-erettsegi-pontszam");
            }
        }
        return $score;
    }

    private function getLanguageExamScore(): int {
        $score = 0;
        foreach ($this->data["tobbletpontok"] as $nyv) {
            if ($nyv["kategoria"] == "Nyelvvizsga") {
                $score += intval(Rules::get("nyelvvizsga-pontok")[$nyv["tipus"]]);
            }
        }
        return $score;
    }

    private function getGraduationnames(): array {
        $subjects = [];
        foreach ($this->data["erettsegi-eredmenyek"] as $d) {
            $subjects[] = $d["nev"];
        }
        return $subjects;
    }

    private function getScoreForSubject(string $subject): int {
        foreach ($this->data["erettsegi-eredmenyek"] as $d) {
            if ($d["nev"] == $subject) {
                return intval($d["eredmeny"]);
            }
        }
        //already checked
        return 0;
    }

}