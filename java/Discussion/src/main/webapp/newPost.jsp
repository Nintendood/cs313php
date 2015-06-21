<%-- 
    Document   : newPost
    Created on : Jun 18, 2015, 6:50:03 PM
    Author     : Adam Harris
--%>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>New Post</title>'
        <style>
            body{
                background-color: black;
            }
            h1, h3, p , a{
	font-family: Consolas, monaco, monospace;
        color:green;
	font-size: 24px;
	font-style: normal;
	font-variant: normal;
	font-weight: 500;
	line-height: 26.3999996185303px;
        }
        h3, p, a{
            font-size:14px;
        }
        a{
            color:blue;
        }
        textarea{
            background-color:black;
            color:green;
        }
        </style>
    </head>
    <body>
        <h1>Enter a New Post</h1>
        
        <p>New post as ${username}</p>
        <form method="POST" action="CreatePost">
            <textarea name="comment" rows="4" cols="50" placeholder="Enter text here"></textarea>
            <input type="submit" value="Submit Post"/>
            <input type="text" value="${username}" name="username" hidden>
        </form>
    </body>
</html>
