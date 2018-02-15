<?php

namespace Enpress\Foundation;

use Illuminate\Support\Collection;

class Hello
{
    /*
     * This is not just a method, it symbolizes the hope and enthusiasm of an entire
     * generation summed up in two words sung most famously by Louis Armstrong:
     * Hello, Dolly.
     */
    public static function quote()
    {
        return Collection::make([
            "Hello, Dolly",
            "Well, hello, Dolly",
            "It's so nice to have you back where you belong",
            "You're lookin' swell, Dolly",
            "I can tell, Dolly",
            "You're still glowin', you're still crowin'",
            "You're still goin' strong",
            "We feel the room swayin'",
            "While the band's playin'",
            "One of your old favourite songs from way back when",
            "So, take her wrap, fellas",
            "Find her an empty lap, fellas",
            "Dolly'll never go away again",
            "Hello, Dolly",
            "Well, hello, Dolly",
            "It's so nice to have you back where you belong",
            "You're lookin' swell, Dolly",
            "I can tell, Dolly",
            "You're still glowin', you're still crowin'",
            "You're still goin' strong",
            "We feel the room swayin'",
            "While the band's playin'",
            "One of your old favourite songs from way back when",
            "Golly, gee, fellas",
            "Find her a vacant knee, fellas",
            "Dolly'll never go away",
            "Dolly'll never go away",
            "Dolly'll never go away again"
        ])->random();
    }
}
