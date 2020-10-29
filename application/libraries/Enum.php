<?php
class Enum {
	function __construct (){}
}

class User_role {
	const SUPER_ADMIN  = 1;
	const SYSTEM_ADMIN = 2;
	const SYSTEM_USER  = 3;
	const SALES        = 4;
	const MEMBER       = 5;
	const VENDOR       = 6;

	public static function getValue($value = NULL)  {
        $class = new ReflectionClass('User_role');
        $constants = $class->getConstants();
        $constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
    }
}

class User_status {
	const INACTIVE    = 0;
	const ACTIVE      = 1;
	const DEACTIVATED = 2;

	public static function getValue($value = NULL) {
        $class = new ReflectionClass('User_status');
        $constants = $class->getConstants();
        $constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
    }
}

class Deleted_status {
	const NOT_DELETED = 0;
	const DELETED 	  = 1;

	public static function getValue($value = NULL) {
        $class = new ReflectionClass('Deleted_status');
        $constants = $class->getConstants();
        $constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
    }
}

class Status {
	const INACTIVE = 0;
	const ACTIVE   = 1;

	public static function getValue($value = NULL) {
		$class = new ReflectionClass('Status');
		$constants = $class->getConstants();
		$constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
	}
}

class Verification_code_type {
	const EMAIL    = 1;
	const MOBILE   = 2;
	const PASSWORD = 3;
}

class Verification_code_status {
	const ACTIVE 	  = 1;
	const VERIFIED 	  = 2;
	const DEACTIVATED = 3;
}

class Verified {
	const NOT_VERIFIED = 0;
	const VERIFIED     = 1;
}

class Device {
	const Android = 1;
	const Ios     = 2;

	public static function getValue($value = NULL) {
		$class = new ReflectionClass('Device');
		$constants = $class->getConstants();
		$constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
	}
}

class Module {
	const Media     = 1;
	const Category  = 3;
	const User      = 6;
	const Tax       = 7;
	const Advertise = 8;
	const Vendor    = 9;
	const Qrcode    = 10;
	
	public static function getValue($value = NULL) {
		$class = new ReflectionClass('Module');
		$constants = $class->getConstants();
		$constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
	}
}

class Action {
	const Add      = 1;
	const Edit     = 2;
	const Delete   = 3;
	const Sign_up  = 4;
	const Sign_in  = 5;
	const Sign_out = 6;
	const Placed   = 7;
	const Cancel   = 8;
	
	public static function getValue($value = NULL) {
		$class = new ReflectionClass('Action');
		$constants = $class->getConstants();
		$constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
	}
}

class Yes_no {
	const No  = 0;
	const Yes = 1;

	public static function getValue($value = NULL) {
		$class = new ReflectionClass('Yes_no');
		$constants = $class->getConstants();
		$constants = toProperCase(array_flip($constants),'_');
		if($value !== NULL) {
			return $constants[$value];
		}
		return $constants;
	}
}
/* end of enum */