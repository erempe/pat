<?php

namespace pat;

/**
 * A template class
 */
class Template implements DesignPattern\IPipeline {

    private $_filename = '';
    private $_templateVariables = array();

    /**
     * Enter description here ...
     * @param String $filename
     * @throws ErrorException
     */
    public function __construct($filename)
    {
        $file = realpath($filename);
        if ( $file === false )
        {
            throw new ErrorException("File $filename not found");
        }
        $this->_filename = $file;
    }

    /**
     * Enter description here ...
     * @param String $variable
     * @throws Exception
     */
    public function __get($variable)
    {
        if ( isset($this->_templateVariables[$variable]) )
        {
            return $this->_templateVariables[$variable];
        }
        else
        {
            throw new Exception('Undefined variable "' . (string) $variable
                    . '"');
        }
    }

    /**
     * Pipe data into 
     * @param mixed $assocData
     * @uses pat::Template::pipe
     */
    function __invoke($assocData)
    {
        return $this->pipe($assocData);
    }

    /**
     * Set data for a template variable
     * @param string $variable
     * @param string $value
     */
    public function __set($variable, $value)
    {
        $this->_templateVariables[$variable] = $value;
    }

    /**
     * Render template with variables replaced by their data
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Send rendered template to console / client
     */
    public function dispatch()
    {
        print $this->render();
    }

    /**
     * Get the used filename of the template
     * @return string Filename of used template
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * Get a specific variable
     * @param String $variableName
     */
    public function getVariable($variableName)
    {
        return $this->__get($variableName);
    }

    /**
     * Returns the whole map of variables
     * @return array
     */
    function getVariables()
    {
        return $this->_templateVariables;
    }

    /**
     * Make an instance by adding a suffix to the filename (before the extension)
     * @param string $suffix the text to add after the name
     * @param boolean $afterExtension add suffix after the extension
     * @return \pat\Template
     */
    public function makeBySuffix($suffix, $afterFileExtension = false)
    {
        if ( $afterFileExtension )
        {
            $extension = pathinfo($this->_filename, PATHINFO_EXTENSION);
            $filename = basename($this->_filename, '.' . $extension)
                    . $suffix . '.' . $extension;
        }
        else
        {
            $filename = $this->_filename . $suffix;
        }

        return new static($filename);
    }

    /**
     * Make an instance with the given filename
     * @param string $filename File to use as template (with extension)
     * @return \pat\Template An instance of this template
     */
    public static function makeTransient($filename)
    {
        return new static($filename);
    }

    /**
     * Pipe objects to templates
     * @see pat.IPipeline::pipe()
     * @return \pat\Template
     * @return \pat\Pattern\Composite
     */
    function pipe($assocData)
    {
        if ( is_array($assocData) )
        {
            
            if ( is_array(current($assocData)) || is_a($assocData, 'Iterator') )
            {
                $pipe = new \pat\Pattern\Composite();

                foreach ( $assocData as $set )
                {
                    $pipe->addCompositeChild($this->pipe($set));
                }

                return $pipe;
            }
            else if ( is_a($assocData, 'Iterator') )
            {
                foreach ($assocData as $key => $value)
                {
                    $this->setVariable($key, $value);
                }
            }
            else
            {
                $this->_templateVariables =
                        array_merge($this->_templateVariables, $assocData);
            }
        }
        else if ( $assocData instanceof \pat\util\Template )
        {
            $this->_templateVariables = array_merge(
                    $this->_templateVariables, $assocData->getVariables()
            );
        }

        return $this;
    }

    /**
     * Render template with variables replaced by their data
     * @return string
     */
    public function render()
    {
        extract($this->_templateVariables);
        ob_start();
        include $this->_filename;
        return ob_get_clean();
    }

    /**
     * Set template variable
     * @param string $variableName
     * @param string $value
     */
    public function setVariable($variableName, $value)
    {
        $this->__set($variableName, $value);
    }

    /**
     * Replace all template variables
     * @param array $assocData
     */
    public function setVariables($assocData)
    {
        if ( false == is_array($assocData) )
        {

            throw new ErrorException("
                Given data is not an array
            ");
        }
        $this->_templateVariables = $assocData;
    }

}