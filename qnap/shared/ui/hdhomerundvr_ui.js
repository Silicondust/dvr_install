/******************************************************************************
 * @file league.js
 * @description
 * @version 1.0
 * @author Rik Dunphy
 *
 * Copyright 2008 (c) Rik Dunphy
 * You are free to use this file in your own programs, but I do not accept
 * any liability for errors or problems arising from it's use. 
 *****************************************************************************/

/**
 * Updates the UI state.
 * @param state 
 *         1 indicates show main Data.
 *         2 indicates show a 1v1 league
 *         3 indicates show add game data page.
 *         4 indicates report loss page.
 *         5 indicates show my fixtures.
 *        10 indicates do nothing (useful for admin bar changes)
 *              
 */
function setUIState(state)
{        
    if (admin_on != 0)
    {
        document.getElementById('layer_admin').style.display='block';
    }
    else
    {
        document.getElementById('layer_admin').style.display='none';
    }
    
    if(state == 10)
    {
    	// keep state as is.
    	return;
    }
    
    if((state == 1) || (state == 2))
    // Display an all clan league.
    {
        document.getElementById('layer_main').style.display='block';
        doAutoRefresh = 0;
    }
    else if(state == 3)
    // Display a 1v1 clan league.
    {
        document.getElementById('layer_main').style.display='none';
    }
    else if(state == 4)
    // Display a 1v1 clan league.
    {
        document.getElementById('layer_main').style.display='none';
    }
    else if(state == 5)
    // Display a 1v1 clan league.
    {
        document.getElementById('layer_main').style.display='none';
    }
    else
    // State unknown/daemon unreachable
    {
        document.getElementById('layer_main').style.display='none';
    }
    doAutoRefresh = 0;
}

/* Set the status message */
function setStatus(msg)
{
    isStatusIdle = 0;
    if(msg == '' || msg == null || msg == undefined)
    {
        isStatusIdle = 1;
        msg = "Idle.";
    }

    document.getElementById('statusMessage').innerHTML = msg;
}

function openLogFile(def_id)
{
    getLogFile(def_id);
}

function deleteLogFile(def_id)
{
    rmLogFile(def_id);
}

function changeRecordPath(def_id)
{
    updateRecordPath(def_id);
}
