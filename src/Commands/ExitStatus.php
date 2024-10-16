<?php

declare (strict_types=1);

namespace Gambol\Commands;

enum ExitStatus: int {
    case SUCCESS = 0;
    case FAILURE = 1;
}
