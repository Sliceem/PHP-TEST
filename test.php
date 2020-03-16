<?php

require_once 'session.php';
session_start();

echo Session::flash('success');