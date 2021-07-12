<?php

namespace App\Forms;

use Illuminate\Support\Carbon;
use Kris\LaravelFormBuilder\Form;

class WorkSheetForm extends Form
{
    public function buildForm()
    {
        $majority = Carbon::now()->subYears(14)->toDateString();
        $older = Carbon::now()->subYears(65)->toDateString();

        $this
            ->add('lastName', 'text', [
                'label' => 'Фамилия',
                'rules' => 'required|string|min:1'
            ])
            ->add('firstName', 'text', [
                'label' => 'Имя',
                'rules' => 'required|string|min:1'
            ])
            ->add('secondName', 'text', [
                'label' => 'Отчество',
                'rules' => 'required|string|min:1'
            ])
            ->add('sex', 'choice', [
                'label' => 'Пол',
                'choices' => [0 => 'Женщина', 1 => 'Мужчина'],
                'selected' => [1],
                'rules' => 'required|numeric|max:1|min:0',
                'error_messages' => ['max' => 'Пол обязателен к заполнению.', 'min' => 'Пол обязателен к заполнению.']
            ])
            ->add('birthDate', 'date', [
                'label' => 'Дата рождения',
                'rules' => 'required|date|after:01-01-1900|after_or_equal:' . $older . '|before_or_equal:' . $majority,
                'value' => $majority
            ])
            ->add('children', 'text', [
                'label' => 'Количество несовершеннолетних детей',
                'rules' => 'required|numeric|min:0|max:30',
                'value' => 0
            ])
            ->add('maritalStatus', 'choice', [
                'label' => 'Семейное положение',
                'choices' => [0 => 'холост/не замужем', 1 => 'женат/замужем'],
                'selected' => [0],
                'rules' => 'required|numeric|max:1|min:0',
                'error_messages' => ['max' => 'Поле обязателен к заполнению.', 'min' => 'Поле обязателен к заполнению.']
            ])
            ->add('income', 'text', [
                'label' => 'Ежемесячный доход',
                'rules' => 'required|numeric|min:0'
            ])
            ->add('employment', 'choice', [
                'label' => 'Тип занятости',
                'choices' => [
                    0 => 'не работаю',
                    1 => 'договор',
                    2 => 'самозанятый',
                    3 => 'индивидуальный предприниматель'],
                'selected' => [0],
                'rules' => 'required|numeric|max:3|min:0',
                'error_messages' => ['max' => 'Поле обязателен к заполнению.', 'min' => 'Поле обязателен к заполнению.']
            ])
            ->add('realEstate', 'checkbox', ['label' => 'Есть ли недвижимость'])
            ->add('outstandingLoans', 'checkbox', ['label' => 'Есть ли непогашенные кредиты'])
            ->add('debtsOnLoans', 'checkbox', ['label' => 'Есть ли задолженности по текущим кредитам'])
            ->add('paymentLoans', 'text', [
                'label' => 'Ежемесячная выплата по текущим кредитам',
                'rules' => 'nullable|required_if:debtsOnLoans,1|numeric|min:0',
                'error_messages' => ['required_if' => 'Ежемесячная выплата по текущим кредитам обязателен к заполнению если есть задолженности по текущим кредитам']
            ])
            ->add('submit', 'submit');

    }
}
