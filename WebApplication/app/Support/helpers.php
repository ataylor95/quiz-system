<?php
    /**
     * This function gets all the question configuration data
	 * Used as a helper function so it can be used in both controllers and blade template files
     * 
     * @return array [number of answers, questions types keys, question types english name]
     */
    function getQuestionsData()
    {
        $questionsData = config('questions');
        $numberAnswers = $questionsData['numAnswers'];
        $types = $questionsData['types'];
        $typeKeys = [];
        $typeValues = [];
        foreach ($types as $key => $value) {
            $typeKeys[] = $key;
            $typeValues[] = $value;
        }
        return [$numberAnswers, $typeKeys, $typeValues];
    }
?>
