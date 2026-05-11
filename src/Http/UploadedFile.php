<?php

namespace Rougin\Slytherin\Http;

use Psr\Http\Message\UploadedFileInterface;

Interop::register('UploadedFile');

/**
 * @package Slytherin
 *
 * @method string|null                       getClientFilename()
 * @method string|null                       getClientMediaType()
 * @method integer                           getError()
 * @method integer|null                      getSize()
 * @method \Psr\Http\Message\StreamInterface getStream()
 * @method void                              moveTo(string $targetPath)
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class UploadedFile extends PsrUploadedFile implements UploadedFileInterface
{
}
