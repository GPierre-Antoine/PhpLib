<?php

namespace PAG\Stream;

/**
 * Class AbstractStreamFilter
 * @package PAG\Stream
 * Provides a quick wrapper for Stream Filters. The users just has to extend the class and implement the applyFilter function.
 */
abstract class AbstractStreamFilter extends \php_user_filter
{
    private $bufferHandle = null;
    private $consumed;
    private $bucket;

    public function filter($in, $out, &$consumed, $closing)
    {
        $this->consumed = $consumed;
        $this->bucket   = $this->makeNewBucket();
        if (false === $this->bucket) {
            return PSFS_ERR_FATAL;
        }
        $this->ReadTransformAndWriteData($in, $out);
        $consumed = $this->consumed;
        return PSFS_PASS_ON;
    }

    private function makeNewBucket()
    {
        $bucket = stream_bucket_new($this->bufferHandle, '');
        return $bucket;
    }

    private function ReadTransformAndWriteData($in, $out)
    {
        $data = $this->read($in);
        $this->setBucketData($this->applyFilter($data));
        $this->writeBucketToOutput($out);
    }

    private function read($in)
    {
        $data = '';
        while ($bucket = stream_bucket_make_writeable($in)) {
            $data           .= $bucket->data;
            $this->consumed += $bucket->datalen;
        }
        return $data;
    }

    private function setBucketData($content): void
    {
        $this->bucket->data = $content;
    }

    /**
     * @param string $data
     * @return mixed
     * <p>Transform the text in the way you want.</p>
     */
    abstract protected function applyFilter(string $data);

    private function writeBucketToOutput($out): void
    {
        stream_bucket_append($out, $this->bucket);
    }

    public function onCreate()
    {
        $this->bufferHandle = fopen('php://temp', 'w+');
        if (false !== $this->bufferHandle) {
            return true;
        }
        return false;
    }

    public function onClose()
    {
        fclose($this->bufferHandle);
    }
}