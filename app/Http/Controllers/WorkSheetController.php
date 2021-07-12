<?php

namespace App\Http\Controllers;

use App\Models\UserCard;
use Illuminate\Routing\Controller as BaseController;
use JMS\Serializer\SerializerBuilder;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Forms\WorkSheetForm;

class WorkSheetController extends BaseController {

    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(WorkSheetForm::class, [
            'method' => 'POST',
            'url' => route('card.store')
        ]);

        return view('card.create', compact('form'));
    }

    public function store(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(WorkSheetForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy())
            ->build();

        $string = json_encode(array_filter($form->getFieldValues()));
        /** @var UserCard $userCard */
        $userCard = $serializer->deserialize($string,  UserCard::class, 'json');
        $userCard->fill($form->getFieldValues());

        return view('card.store', ['score' => $userCard->getScore()]);

    }
}
