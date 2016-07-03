use \Dingo\Api\Routing\Helpers;

    /**
     * The controller's default status code
     *
     * @var int
     */
    protected $statusCode = \Dingo\Api\Http\Response::HTTP_OK;

    /**
     * Validate the controller input
     *
     * @param $action
     */
    public function validateInput($action)
    {
        if ($action == 'create')
            $rules = $this->create_rules;
        else
            $rules = $this->update_rules;

        $this->validateRequest($rules);
    }

    /**
     * Handles the actual request validation
     *
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return mixed
     */
    public function validateRequest(array $rules, array $messages = [], array $customAttributes = [])
    {
        $request = app('request');
        $validator = app('validator')->make($request->all(), $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            return $this->returnValidationErrors($validator);
        }
    }

    /**
     * Returns the validation errors
     *
     * @param $validator
     * @return mixed
     */
    public function returnValidationErrors($validator)
    {
        return $this->response()->array([
            'message'   => 'Validation error',
            'errors'        => $validator->errors()
        ])->setStatusCode(422);
    }

