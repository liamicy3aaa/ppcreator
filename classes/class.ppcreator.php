<?php

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Slide\Background\Image;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\Style\Font;

/**
 * Author: Liam McClelland
 * Copyright: Gadgetfreak Systems
 * */

class ppcreator {
    
    private static $protocol = "https";
    
    public static function buildQuizArray($data = false) {
        
        $data = (($data === false) ? $_POST : $data);
        
        // Seperating questions by round number
    $qs = array();
    
    foreach($data["quiz"] as $item) {
        
        if(!isset($qs[$item[0]])) {
            
            $qs[$item[0]] = array();
            
        }
        
        $qs[$item[0]][] = array($item[1], $item[2]);
        
    }
    
    // Building final quiz array for builder
    $quiz = array();
    
    foreach($data["categories"] as $category) {
        
        $quiz[$category[0]] = array(
                "category" => str_replace("_", ",", $category[1]),
                "questions" => array()
            );
            
        foreach($qs[$category[0]] as $q) {
            
            $quiz[$category[0]]["questions"][] = array(
                    "q" => str_replace("_", ",", $q[0]),
                    "a" => str_replace("_", ",", $q[1])
                );
            
        }    
        
    }

    return $quiz;
        
    }
    
    public static function createPresentation($quiz, $filepath = "files/") {

        $objPHPPowerPoint = new PhpPresentation();

        $objPHPPowerPoint->getLayout()
                         ->setCX(960, DocumentLayout::UNIT_MILLIMETER)
                         ->setCY(540, DocumentLayout::UNIT_MILLIMETER);
        
        // Create slide
        $currentSlide = $objPHPPowerPoint->getActiveSlide();
        
            // Set background
            $oBkgImage = new Image();
        $oBkgImage->setPath('resources/bg.png');
        
        $oBkgImage2 = new Image();
        $oBkgImage2->setPath('resources/question_bg.png');
        
        // Intro Image
        $oBkgImage3 = new Image();
        $oBkgImage3->setPath('resources/quiz_night.png');
        
        // Create a shape (text)
        $currentSlide->setBackground($oBkgImage3);
        
        $introSlide = $objPHPPowerPoint->createSlide();
        $introSlide->SetBackground($oBkgImage);
          
          
          foreach($quiz as $round => $content) {
              
                $roundSlide = $objPHPPowerPoint->createSlide();
                $shape = $roundSlide->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1300)
                  ->setOffsetX(700)
                  ->setOffsetY(800);
                $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
                $textRun = $shape->createTextRun('Round ' . $round);
                $textRun->getFont()->setSize(180)
                                   ->setName("Arial")
                                   ->setColor( new Color( 'FFFFFF' ) );
                                   
                $shape2 = $roundSlide->createRichTextShape()
                      ->setHeight(300)
                      ->setWidth(1300)
                      ->setOffsetX(700)
                      ->setOffsetY(1100);
                $shape2->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
                $textRun = $shape2->createTextRun($content["category"]);
                $textRun->getFont()->setSize(70)
                                   ->setName('Arial')
                                   ->setColor( new Color( 'FFFFFF' ) );   
                           $roundSlide->setBackground($oBkgImage);
            
            foreach($content["questions"] as $qnumber => $question) {
                
                 // Create quiz questions
              
            $oSlide2 = $objPHPPowerPoint->createSlide();       
                               
            // Round category
            $shape = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1500)
                  ->setOffsetX(300)
                  ->setOffsetY(370);
            //$shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun($content["category"]);
            $textRun->getFont()->setSize(70)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setname('Arial');  
            
            // Round label
            $shape = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(600)
                  ->setOffsetX(1500)
                  ->setOffsetY(550)
                  ->setRotation(90);
            //$shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun('Round ' . $round);
            $textRun->getFont()->setSize(70)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setname('Arial');
            
            // Question Number
            $shape = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(600)
                  ->setOffsetX(190)
                  ->setOffsetY(550);
            //$shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun('Q'. ($qnumber + 1));
            $textRun->getFont()->setSize(120)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setname('Arial');
                               
            // Question Text
            $shape2 = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1600)
                  ->setOffsetX(190)
                  ->setOffsetY(780);
            //$shape2->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape2->createTextRun($question["q"]);
            $textRun->getFont()->setSize(100)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setName('Arial');                   
              
            $oSlide2->setBackground($oBkgImage2);
                
            }
          }
          
          // Create answers section

        foreach($quiz as $round => $content) {
            
            $answerSlide = $objPHPPowerPoint->createSlide();
                $shape = $answerSlide->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1300)
                  ->setOffsetX(700)
                  ->setOffsetY(800);
                $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
                $textRun = $shape->createTextRun('Round ' . $round);
                $textRun->getFont()->setSize(180)
                                   ->setName("Arial")
                                   ->setColor( new Color( 'FFFFFF' ) );
                                   
                $shape2 = $answerSlide->createRichTextShape()
                      ->setHeight(300)
                      ->setWidth(1300)
                      ->setOffsetX(700)
                      ->setOffsetY(1100);
                $shape2->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
                $textRun = $shape2->createTextRun("Answers");
                $textRun->getFont()->setSize(70)
                                   ->setName('Arial')
                                   ->setColor( new Color( 'FFFFFF' ) );   
                           $answerSlide->setBackground($oBkgImage);
            
            foreach($content["questions"] as $qnumber => $question) {
        
            // Create quiz answers
            
            $oSlide2 = $objPHPPowerPoint->createSlide();       
                               
            // Round category
            $shape = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1500)
                  ->setOffsetX(300)
                  ->setOffsetY(370);
            //$shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun($content["category"]);
            $textRun->getFont()->setSize(70)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setname('Arial');  
                               
            // question answer
            $shape = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1500)
                  ->setOffsetX(300)
                  ->setOffsetY(1750);
            //$shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun($question["a"]);
            $textRun->getFont()->setItalic(true)
                                ->setSize(80)
                                ->setColor( new Color( 'FFFF00' ) )
                                ->setname('Arial');                   
            
            // Round label
            $shape = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1000)
                  ->setOffsetX(1300)
                  ->setOffsetY(750)
                  ->setRotation(90);
            //$shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun('Round ' . ($round) . ' Answers');
            $textRun->getFont()->setSize(70)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setname('Arial');
            
            // Question Number
            $shape = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(600)
                  ->setOffsetX(190)
                  ->setOffsetY(550);
            //$shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape->createTextRun('Q'. ($qnumber + 1));
            $textRun->getFont()->setSize(120)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setname('Arial');
                               
            // Question Text
            $shape2 = $oSlide2->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1600)
                  ->setOffsetX(190)
                  ->setOffsetY(780);
            //$shape2->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
            $textRun = $shape2->createTextRun($question["q"]);
            $textRun->getFont()->setSize(100)
                               ->setColor( new Color( 'FFFFFF' ) )
                               ->setName('Arial');                   
              
            $oSlide2->setBackground($oBkgImage2);
                  
            }
        }
        
        // Final Slide
        $finalSlide = $objPHPPowerPoint->createSlide();
                $shape = $finalSlide->createRichTextShape()
                  ->setHeight(300)
                  ->setWidth(1300)
                  ->setOffsetX(700)
                  ->setOffsetY(800);
                $shape->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
                $textRun = $shape->createTextRun('Thank you!');
                $textRun->getFont()->setSize(180)
                                   ->setName("Arial")
                                   ->setColor( new Color( 'FFFFFF' ) );
                                   
                $shape2 = $finalSlide->createRichTextShape()
                      ->setHeight(300)
                      ->setWidth(1300)
                      ->setOffsetX(700)
                      ->setOffsetY(1100);
                $shape2->getActiveParagraph()->getAlignment()->setHorizontal( Alignment::HORIZONTAL_CENTER );
                $textRun = $shape2->createTextRun("Join us again soon for another interesting Quiz.");
                $textRun->getFont()->setSize(70)
                                   ->setName('Arial')
                                   ->setColor( new Color( 'FFFFFF' ) );   
                
                $finalSlide->setBackground($oBkgImage);
        
        $filePath = $filepath. "quiz_" . date("d-m-Y H:i:s", time()) . ".pptx";
        $oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
        $oWriterPPTX->save($filePath);
        
        return array(
            "status" => true,
            "file" => self::$protocol . "://" . $_SERVER["HTTP_HOST"] . "/$filePath"
            );
        
    }
    
}