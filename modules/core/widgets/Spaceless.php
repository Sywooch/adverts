<?php

namespace app\modules\core\widgets;

class Spaceless extends \yii\widgets\Spaceless
{
    /**
     * @var string
     */
    public $text;

    /**
     * @var bool
     */
    public $return = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->text) {
            ob_start();
            ob_implicit_flush(false);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->text) {
            $this->text = ob_get_clean();
        }
        $text = trim(preg_replace('/>\s+</', '><', $this->text));
        if ($this->return){
            return $text;
        } else {
            echo $text;
        }
    }
}
