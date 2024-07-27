<?php

namespace App\Enum;

enum QuestionType: string
{
   case MULTIPLE_CHOICE = 'multiple_choice';
   case MATCHING = 'matching';
   case SORTING = 'sorting';
   case FILL_BLANK = 'fill_blank';
   case CONSTRUCTION = 'construction';

}
