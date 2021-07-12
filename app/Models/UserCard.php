<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use JMS\Serializer\Annotation as JMS;

class UserCard extends Model
{
    /**
     * @var string
     * @JMS\Type("string")
     */
    public string $lastName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    public string $firstName;

    /**
     * @var string
     * @JMS\Type("string")
     */
    public string $secondName;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public int $sex = 0;

    /**
     * @var string
     * @JMS\Type("string")
     */
    public string $birthDate;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public int $children = 0;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public int $maritalStatus = 0;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public int $income = 0;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public int $employment = 0;

    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    public bool $realEstate = false;

    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    public bool $outstandingLoans = false;

    /**
     * @var bool
     * @JMS\Type("boolean")
     */
    public bool $debtsOnLoans = false;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    public int $paymentLoans = 0;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    protected int $point = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lastName',
        'firstName',
        'secondName',
        'sex',
        'birthDate',
        'children',
        'maritalStatus',
        'income',
        'employment',
        'realEstate',
        'outstandingLoans',
        'debtsOnLoans',
        'paymentLoans',
        'point',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'point',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'lastName' => 'string',
        'firstName' => 'string',
        'secondName' => 'string',
        'sex' => 'integer',
        'birthDate' => 'string',
        'children' => 'integer',
        'maritalStatus' => 'integer',
        'income' => 'integer',
        'employment' => 'integer',
        'realEstate' => 'integer',
        'outstandingLoans' => 'boolean',
        'debtsOnLoans' => 'boolean',
        'paymentLoans' => 'integer',
        'point' => 'integer',
    ];

    /**
     * Оценка анкеты.
     *
     * @return integer
     */
    public function getScore(): int
    {

        $birthDate = new Carbon($this->birthDate);
        $timeNow = new Carbon();

        $this->point = 0;

        //Возраст менее 18 лет
        if ($birthDate > ($timeNow->clone())->subYears(18)) {
            $this->point += 5;
        }

        //Возраст более 30 лет
        //Мужской пол
        //Семейное положение «Холост»
        if ($birthDate <= ($timeNow->clone())->subYears(30) && $this->sex && !$this->maritalStatus) {

            //Ежемесячный доход менее 25 000 руб.
            //Нет детей
            if ($this->income < 25000 && !$this->children) {
                $this->point += 2;
            }
            //Ежемесячный доход менее 30 000 руб.
            //Есть несовершеннолетние дети
            elseif ($this->income < 30000 && $this->children) {
                $this->point += 3;
            }

        }

        //Возраст более 26 лет
        //Женский пол
        //Семейное положение «Не замужем»
        if ($birthDate <= ($timeNow->clone())->subYears(26) && !$this->sex && !$this->maritalStatus) {

            //Ежемесячный доход менее 22 000 руб.
            //Нет детей
            if ($this->income < 22000 && !$this->children) {
                $this->point += 2;
            }
            //Ежемесячный доход менее 28 000 руб.
            //Больше 2 несовершеннолетних детей
            elseif ($this->income < 28000 && $this->children > 2) {
                $this->point += 3;
            }

        }

        //Возраст более 65 лет
        //Есть просрочки по текущим кредитам
        //Не работает
        if ($birthDate > ($timeNow->clone())->subYears(65) && $this->debtsOnLoans && !$this->employment) {
            $this->point += 3;
        }

        //Есть просрочки по кредитам
        //Сумма текущих выплат больше 50% от ежемесячного дохода
        if ($this->debtsOnLoans && $this->income / 2 < $this->paymentLoans) {
            $this->point += 3;
        }

        //Возраст от 18 лет
        if ($birthDate < ($timeNow->clone())->subYears(18)) {

            //Ежемесячный доход менее 15 000 руб.
            if ($this->income < 15000) {
                $this->point += 2;
            }
            //Возраст от 18 до 35 лет
            if ($birthDate >= ($timeNow->clone())->subYears(35)) {

                //Один несовершеннолетний ребенок
                if ($this->children == 1) {
                    $this->point += 1;
                }
                //Более одного несовершеннолетнего ребенка
                elseif ($this->children > 1) {
                    $this->point += 2;
                }
            }
        }

        return $this->point;
    }

}
