<?php
 namespace MailPoetVendor\Symfony\Component\DependencyInjection\Compiler; if (!defined('ABSPATH')) exit; interface RepeatablePassInterface extends CompilerPassInterface { public function setRepeatedPass(RepeatedPass $repeatedPass); } 