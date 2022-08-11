<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Response;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

class Csv
{
    /**
     * CSVアップロード（CSV 取り込み）
     * @param file $file
     * @param array $header
     * @return rows
     **/
    public function parse($file)
    {
        // Goodby CSVのconfig設定
        $config = new LexerConfig();
        $interpreter = new Interpreter();
//        $interpreter->unstrict();
        $lexer = new Lexer($config);

        // CharsetをUTF-8に変換
        $config->setToCharset("UTF-8");
        $config->setFromCharset("sjis-win");

        // CSVデータをパース
        $rows = array();
        try {
            $interpreter->addObserver(function(array $row) use (&$rows) {
                $rows[] = $row;
            });
            $lexer->parse($file, $interpreter);
        } catch (\Exception $e) {
            throw $e;
        }

        // １行ずつ処理
        $data = array();
        foreach ($rows as $key => $value) {

            // 最初の行はヘッダー
            if($key == 0) {
                $header = $value;
                continue;
            }

            // 配列化 - ２行目以降はヘッダーに沿って配列に
            foreach ($value as $k => $v) {
                $data[$key][$header[$k]] = $v;
            }
        }

        // ＣＳＶを配列で戻す
        return $data;
    }
}