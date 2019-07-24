<?php
/**
 * Created by PhpStorm.
 * User: raph
 * Date: 15/03/19
 * Time: 14:00
 */

namespace Apistarter\Sdk\Exception\Custom;



class ValidationException extends SdkCustomException
{
    const uuid = '6b30ec2f-bd72-460a-a576-85c4f076df62';

    public function getViolations() {
        return $this->getResponseData()->validation_errors;
    }

    public function getViolation($uuid) {
        foreach($this->getViolations() as $violation) {
            if($violation->code === $uuid) {
                return $violation;
            }
        }
        return null;
    }
}