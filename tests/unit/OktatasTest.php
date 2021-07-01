<?php

use PHPUnit\Framework\TestCase;
use app\ScoreCalculator;
use app\Utility;

class OktatasTest extends TestCase
{
    /**
     * @var ScoreCalculator
     */
    private $sc;

    public function __construct(?string $name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        Utility::setBase("../../");
        $this->sc = ScoreCalculator::getInstance();
    }

    public function testGraduation0() {
        $this->assertEquals($this->sc->getStudentByIndex(0)->isGraduationValid(), true);
    }

    public function testGraduation1() {
        $this->assertEquals($this->sc->getStudentByIndex(1)->isGraduationValid(), true);
    }

    public function testGraduation2() {
        $this->assertEquals($this->sc->getStudentByIndex(2)->isGraduationValid(), true);
    }

    public function testGraduation3() {
        $this->assertEquals($this->sc->getStudentByIndex(3)->isGraduationValid(), false);
    }

    public function testRequiredSubjects0() {
        $this->assertEquals($this->sc->getStudentByIndex(0)->hasRequiredSubjects(), true);
    }

    public function testRequiredSubjects1() {
        $this->assertEquals($this->sc->getStudentByIndex(1)->hasRequiredSubjects(), true);
    }

    public function testRequiredSubjects2() {
        $this->assertEquals($this->sc->getStudentByIndex(2)->hasRequiredSubjects(), false);
    }

    public function testRequiredSubjects3() {
        $this->assertEquals($this->sc->getStudentByIndex(3)->hasRequiredSubjects(), true);
    }

    public function testStudentForFacultyRequirements0() {
        $this->assertEquals($this->sc->getStudentByIndex(0)->isGraduationValidForFaculty(), true);
    }

    public function testStudentForFacultyRequirements1() {
        $this->assertEquals($this->sc->getStudentByIndex(1)->isGraduationValidForFaculty(), true);
    }

    public function testStudentForFacultyRequirements2() {
        $this->assertEquals($this->sc->getStudentByIndex(2)->isGraduationValidForFaculty(), true);
    }

    public function testStudentForFacultyRequirements3() {
        $this->assertEquals($this->sc->getStudentByIndex(3)->isGraduationValidForFaculty(), true);
    }

    public function testBaseScore0() {
        $this->assertEquals($this->sc->getStudentByIndex(0)->getBaseScore(), 370);
    }

    public function testBaseScore1() {
        $this->assertEquals($this->sc->getStudentByIndex(1)->getBaseScore(), 376);
    }

    public function testPlusScore0() {
        $this->assertEquals($this->sc->getStudentByIndex(0)->getPlusScore(), 100);
    }

    public function testPlusScore1() {
        $this->assertEquals($this->sc->getStudentByIndex(1)->getPlusScore(), 100);
    }

}