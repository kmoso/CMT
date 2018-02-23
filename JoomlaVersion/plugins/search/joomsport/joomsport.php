<?php
/**
 * @version             $Id: joomsport.php 1.0.0 Beta
 * @copyright           Copyright (C) 2005 - 2010 JoomBeard Solutions. All rights reserved.
 * @license             GNU/GPL
 * Search Plugin for JoomSport Components. Provide search capabilities over players, teams, matches (and extra fields)
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.plugin.plugin');

class plgSearchJoomsport extends JPlugin
{
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	function onContentSearchAreas() {
		
		 static $areas = array(
			'joomsport' => 'JoomSport'
		);
		return $areas;
	}
	
	
	function onContentSearch($text, $phrase='', $ordering='', $areas=null)
	{
		$mainframe = JFactory::getApplication();
		$Itemid = JRequest::getInt('Itemid'); 
		 
		// var_dump($this->params);
		  $plugin = JPluginHelper::getPlugin( 'search', 'joomsport' );
		  $plgParams = $this->params;//( $plugin->params );
		  $limit = ( $plgParams->get( 'search_limit' ) );
		  $search_sections = array('Players','Teams','Match');

			$db    = JFactory::getDBO();
			$user  = JFactory::getUser(); 
		
		$where_players = "''";
		$where_players_extra = "''";
		$where_teams         = "''";
		$where_teams_extra   = "''";
		$where_match         = "''";
		$where_match_extra   = "''";


			if (is_array( $areas )) {
				if (!array_intersect( $areas, array_keys( $this->onContentSearchAreas() ) )) {
				 alert('No Areas For Search');
					return array();
				}
			}
		 
		   
		$text = trim( $text );
		 
		if ($text == '') {
				return array();
			}
			$allrows = array(); 
			$wheres = array();
			$wheresteam = array();
			switch ($phrase) {
		 
		//search exact
				case 'exact':
					$text          = $db->Quote( '%'.$db->getEscaped( $text, true ).'%', false );
					$wheres2       = array();
					$wheres2[]   = 'LOWER(a.last_name) LIKE '.$text;
					$where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
					break;
		 
		//search all or any
				case 'all':
				case 'any':
		 
		//set default
				default:
					$words          = explode( ' ', $text );
					$wheres_players = array();
					$wheres_players_extra = array();
					$wheres_teams = array();
					$wheres_teams_extra = array();
					$wheres_match = array();
					$wheres_match_extra = array();
					
					
					foreach ($words as $word)
					{
					   $word          = $db->Quote( '%'.$db->getEscaped( $word, true ).'%', false );
					   $wheres2_players       = array();
					   $wheres2_players_extra = array();
					   $wheres2_teams         = array();
					   $wheres2_teams_extra   = array();
					   $wheres2_match         = array();
					   $wheres2_match_extra   = array();
						
					// players where...
					if ( $plgParams->get( 'players' )) 
						{
						$wheres2_players[]   = 'LOWER(a.last_name) LIKE '.$word;
						$wheres2_players[]   = 'LOWER(a.first_name) LIKE '.$word;
						$wheres2_players[]   = 'LOWER(a.nick) LIKE '.$word;
						$wheres2_players[]   = 'LOWER(a.about) LIKE '.$word;
						//team playing...
						$wheres2_players[]   = 'LOWER(t.t_name) LIKE '.$word;
						$wheres_players[]    = implode( ' OR ', $wheres2_players);
						
						$wheres2_players_extra[]   = 'LOWER(exv.fvalue) LIKE '.$word;
						$wheres_players_extra[]    = implode( ' OR ', $wheres2_players_extra);
						}
				    
					  // teams where...
					  if ( $plgParams->get( 'teams' ) ) 
						{
						$wheres2_teams[]   = 'LOWER(a.t_name) LIKE '.$word;
						$wheres2_teams[]   = 'LOWER(a.t_descr) LIKE '.$word;
						$wheres2_teams[]   = 'LOWER(a.t_city) LIKE '.$word;
						$wheres_teams[]    = implode( ' OR ', $wheres2_teams);
						
						$wheres2_teams_extra[]   = 'LOWER(exv.fvalue) LIKE '.$word;
						$wheres_teams_extra[]    = implode( ' OR ', $wheres2_teams_extra);
						}
					       
					  // match where...
					  if ( $plgParams->get( 'matches' ) ) 
						{
						$wheres2_match[]   = 'LOWER(m.match_descr) LIKE '.$word;
						$wheres2_match[]   = 'LOWER(m.m_location) LIKE '.$word;
						$wheres2_match[]   = 'LOWER(t1.t_name) LIKE '.$word;
						$wheres2_match[]   = 'LOWER(t2.t_name) LIKE '.$word;
						$wheres_match[]    = implode( ' OR ', $wheres2_match);
						
						$wheres2_match_extra[]   = 'LOWER(exv.fvalue) LIKE '.$word;
						$wheres_match_extra[]    = implode( ' OR ', $wheres2_match_extra);
						}
						
					}
					//print_r($wheres_teams);echo "</br>";
					$where_players       = $wheres_players?'(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres_players ) . ')':("''");
					$where_players_extra = $wheres_players_extra?'(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres_players_extra ) . ')':("''");
					$where_teams         = $wheres_teams?'(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres_teams ) . ')':("''");
					$where_teams_extra   = $wheres_teams_extra?'(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres_teams_extra ) . ')':("''");
					$where_match         = $wheres_match?'(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres_match ) . ')':("''");
					$where_match_extra   = $wheres_match_extra?'(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres_match_extra ) . ')':("''");
					break;
			}
			//print_r($where_teams);die;
		
		
		//die;	
			//ordering of the results
			switch ( $ordering ) {
		 
		//alphabetic, ascending
				case 'alpha':
					$order_players = 'a.last_name ASC';
					$order_teams   = 'a.t_descr ASC';
					$order_match   = 'm.id DESC';
					break;
		 
		//oldest first
				case 'oldest':
		 
		//popular first
				case 'popular':
		 
		//newest first
				case 'newest':
		 
		//default setting: alphabetic, ascending
				default:
					$order_players = 'a.last_name ASC';
					$order_teams   = 'a.t_descr ASC';
					$order_match   = 'm.id DESC';
			}

		$searchjoomsport = JText::_( 'JoomSport' );

		//PLAYERS 
			//search players query
			$query = 'SELECT a.id as playerid, '
			. ' a.first_name as firstname, '
			. ' a.last_name as lastname, '
			. ' a.nick as nickname, '
			. ' a.about as aboutplayer, '
			. ' t.t_name as teamname, '
			. ' "1" AS browsernav,'
			. ' "0" AS section '        
			. ' FROM #__bl_players as a, #__bl_teams as t '
			. ' WHERE ( '. $where_players .' )'
			. ' AND a.team_id = t.id '
		       /* . ' AND a.published = 1'*/
		       /* . ' AND b.access <= '. (int) $user->get( 'aid' )*/
			. ' ORDER BY '. $order_players
			;
		      
			$db->setQuery( $query, 0, $limit );
			$rows_players = $db->loadObjectList();
			if(count($rows_players)){
			foreach($rows_players as $key => $row) 
				{
				$rows_players[$key]->href = 'index.php?option=com_joomsport&task=player&sid=0&id='.$row->playerid.'&Itemid='.$Itemid;
				$rows_players[$key]->title = $row->lastname." ".$row->nickname." ".$row->firstname." (".$row->teamname.")";
				$rows_players[$key]->section= JText::_($search_sections[$row->section]);
				$rows_players[$key]->text= $row->aboutplayer;
				}
			}	
			$allrows[] = $rows_players;

		//PLAYERS EXTRA FIELDS

			//search players extra fields query
			$query = 'SELECT a.id as playerid, '
			. ' a.first_name as firstname, '
			. ' a.last_name as lastname, '
			. ' a.nick as nickname, '
			. '    concat(ex.name," : ",exv.fvalue) as aboutplayer, '
			. ' t.t_name as teamname, '
			. ' "1" AS browsernav,'
			. ' "0" AS section '        
			. ' FROM #__bl_players as a, #__bl_teams as t, #__bl_extra_values as exv, #__bl_extra_filds as ex '
			. ' WHERE ( '. $where_players_extra .' )'
			. ' AND ex.type=0 '
			. ' AND ex.published=1 '
			. ' AND ex.id=exv.f_id '
			. ' AND exv.uid=a.id '
			. ' AND a.team_id = t.id '
		       /* . ' AND b.access <= '. (int) $user->get( 'aid' )*/
			. ' ORDER BY '. $order_players
			;
		      
			$db->setQuery( $query, 0, $limit );
			$rows_players_extra = $db->loadObjectList();
        if($rows_players_extra){
			foreach($rows_players_extra as $key => $row) 
				{
				$rows_players_extra[$key]->href = 'index.php?option=com_joomsport&task=player&sid=0&id='.$row->playerid.'&Itemid='.$Itemid;
				$rows_players_extra[$key]->title = $row->lastname." ".$row->nickname." ".$row->firstname." (".$row->teamname.")";
				$rows_players_extra[$key]->section= JText::_($search_sections[$row->section]);
				$rows_players_extra[$key]->text= $row->aboutplayer;
				}
        }
			$allrows[] = $rows_players_extra;
			
		//TEAMS

			//search teams query
			$query = 'SELECT a.id as teamid, '
			. ' a.t_name , '
			. ' a.t_descr, '
			. ' a.t_city, '
			. ' st.season_id, '
			. ' "1" AS browsernav,'
			. ' "1" AS section '        
			. ' FROM #__bl_teams as a, #__bl_season_teams as st '
			. ' WHERE ( '. $where_teams .' )'
			. ' AND st.team_id=a.id '
		       /* . ' AND a.published = 1'*/
		       /* . ' AND b.access <= '. (int) $user->get( 'aid' )*/
			. ' ORDER BY '. $order_teams
			 ;
		      
			 $db->setQuery( $query, 0, $limit );
			$rows_teams = $db->loadObjectList();
        if($rows_teams){
			foreach($rows_teams as $key => $row) 
				{
			    //    echo '<a href="'.JRoute::_('index.php?option=com_joomsport&task=edit_team&tid='.$team['tid'].'&sid='.$this->sid.'&
				$rows_teams[$key]->href = 'index.php?option=com_joomsport&task=team&tid='.$row->teamid.'&sid='.$row->season_id.'&Itemid='.$Itemid;
				$rows_teams[$key]->title = $row->t_name." (".$row->t_city.")";
				$rows_teams[$key]->section= JText::_($search_sections[$row->section]);
				$rows_teams[$key]->text= $row->t_descr;
				}
        }
			$allrows[] = $rows_teams;

		//TEAMS EXTRA FIELDS

			//search teams query
			$query = 'SELECT a.id as teamid, '
			. ' a.t_name , '
			. '    concat(ex.name," : ",exv.fvalue) as t_descr, '
			. ' a.t_city, '
			. ' st.season_id, '
			. ' "1" AS browsernav,'
			. ' "1" AS section '        
			. ' FROM #__bl_teams as a, #__bl_season_teams as st, #__bl_extra_values as exv, #__bl_extra_filds as ex '
			. ' WHERE ( '. $where_teams_extra .' )'
			. ' AND st.team_id=a.id '
			. ' AND ex.type=1 '
			. ' AND ex.published=1 '
			. ' AND ex.id=exv.f_id '
			. ' AND exv.uid=a.id '
		       /* . ' AND a.published = 1'*/
		       /* . ' AND b.access <= '. (int) $user->get( 'aid' )*/
			. ' ORDER BY '. $order_teams
			 ;
		      
			$db->setQuery( $query, 0, $limit );
			$rows_teams_extra = $db->loadObjectList();
        if($rows_teams_extra){
			foreach($rows_teams_extra as $key => $row) 
				{
				//Shows the team one time per season.... (see it for fixing?)
				$rows_teams_extra[$key]->href = 'index.php?option=com_joomsport&task=team&tid='.$row->teamid.'&sid='.$row->season_id.'&Itemid='.$Itemid;
				$rows_teams_extra[$key]->title = $row->t_name." (".$row->t_city.")";
				$rows_teams_extra[$key]->section= JText::_($search_sections[$row->section]);
				$rows_teams_extra[$key]->text= $row->t_descr;
				}
        }
			$allrows[] = $rows_teams_extra;
			
		// MATCHES
		$query = ' select m.id as matchid, '
		       . 'md.id as md_id, '
		       . 'md.s_id as season_id,'
		       . 'm.m_location, '
		       . '  m.match_descr, '
		       . '  md.m_name as matchday,'
		       . '  t1.t_name as team1,'
		       . '  t2.t_name as team2,'
		       . '  tour.name as tournament,'
		       . '  s.s_name as season,'
		       . ' "1" AS browsernav,'
		       . ' "2" AS section, '        
		       . '  concat(cast(m.score1 as char(1)),":",cast(m.score2 as char(1))) as score, '
		       . '  concat(m.m_location, ". ", m.match_descr) as match_text'
		       . '  FROM #__bl_matchday as md, #__bl_seasons as s, #__bl_tournament as tour, #__bl_match as m '
		       . ' LEFT JOIN #__bl_teams as t1 ON t1.id = m.team1_id '
		       . ' LEFT JOIN #__bl_teams as t2 ON t2.id = m.team2_id   '
		       . ' WHERE ( '. $where_match .' )'
		       . ' AND m.m_id=md.id '
		       . ' AND md.s_id=s.s_id '
		       . ' AND s.t_id=tour.id '
		       . ' ORDER BY '. $order_match
			 ;
			
			$db->setQuery( $query, 0, $limit );
			$rows_match= $db->loadObjectList();
        if($rows_match){
			foreach($rows_match as $key => $row) 
				{
				$rows_match[$key]->href = 'index.php?option=com_joomsport&task=view_match&id='.$row->matchid.'&Itemid='.$Itemid;
				$rows_match[$key]->title = $row->team1. " - ".$row->team2." = ".$row->score. " (".$row->tournament." - ".$row->season." - ".$row->matchday.")";
				$rows_match[$key]->section= JText::_($search_sections[$row->section]);
				$rows_match[$key]->text= $row->match_descr;
				$rows_match[$key]->text= $row->match_text;
				}
        }
			$allrows[] = $rows_match;    
			
		//MATCHES EXTRA FIELDS


		$query = ' select m.id as matchid, '
		       . 'md.id as md_id, '
		       . 'md.s_id as season_id,'
		       . 'm.m_location, '
		       . '  m.match_descr, '
		       . '  md.m_name as matchday,'
		       . '  t1.t_name as team1,'
		       . '  t2.t_name as team2,'
		       . '  tour.name as tournament,'
		       . '  s.s_name as season,'
		       . ' "1" AS browsernav,'
		       . ' "2" AS section, '        
		       . '  concat(cast(m.score1 as char(1)),":",cast(m.score2 as char(1))) as score, '
		       . '  concat(ex.name," : ",exv.fvalue) as match_text '
		       . '  FROM #__bl_matchday as md, #__bl_seasons as s, #__bl_tournament as tour, #__bl_extra_values as exv, #__bl_extra_filds as ex, #__bl_match as m  '
		       . ' LEFT JOIN #__bl_teams as t1 ON t1.id = m.team1_id '
		       . ' LEFT JOIN #__bl_teams as t2 ON t2.id = m.team2_id   '
		       . ' WHERE ( '. $where_match_extra .' )'
		       . ' AND m.m_id=md.id '
		       . ' AND md.s_id=s.s_id '
		       . ' AND s.t_id=tour.id '
		       . ' AND ex.type=2 '
		       . ' AND ex.published=1 '
		       . ' AND ex.id=exv.f_id '
		       . ' AND exv.uid=m.id '
		      /* . ' AND a.published = 1'*/
		      /* . ' AND b.access <= '. (int) $user->get( 'aid' )*/
		       . ' ORDER BY '. $order_match
			 ;
		   
			$db->setQuery( $query, 0, $limit );
			$rows_match_extra = $db->loadObjectList();
        if($rows_match_extra){
			  foreach($rows_match_extra as $key => $row) 
				{
				$rows_match_extra[$key]->href = 'index.php?option=com_joomsport&task=view_match&id='.$row->matchid.'&Itemid='.$Itemid;
				$rows_match_extra[$key]->title = $row->team1. " - ".$row->team2." = ".$row->score. " (".$row->tournament." - ".$row->season." - ".$row->matchday.")";
				$rows_match_extra[$key]->section= JText::_($search_sections[$row->section]);
				$rows_match_extra[$key]->text= $row->match_descr;
				$rows_match_extra[$key]->text= $row->match_text;
				}
        }
			$allrows[] = $rows_match_extra;   
			
			
			
			
		    

		 $results = array();
		   if ( count( $allrows ) ) {
		      foreach( $allrows as $row ) {
			 $results = array_merge( $results, ( array ) $row );
		      }
		      }


			
			
		return $results;
	}
	
}