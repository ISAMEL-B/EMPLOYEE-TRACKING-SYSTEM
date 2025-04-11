$(function() {
  var INDEX = 0;
  var questionCount = 0; // Track the number of questions asked

  $("#chat-submit").click(function(e) {
    e.preventDefault();
    var msg = $("#chat-input").val();
    if (msg.trim() == '') {
      return false;
    }
    generate_message(msg, 'self');
    
    // Increment the question count
    questionCount++;

    setTimeout(function() {
      if (questionCount <= 20) {
        var fakeResponse = generate_bot_response(msg, questionCount);
        generate_button_message(fakeResponse, 'user');
      } else {
        generate_button_message(['You’ve reached the maximum number of questions. Please feel free to ask anything else!'], 'user');
      }
    }, 1000);
  });

  function generate_message(msg, type) {
    INDEX++;
    var str = "";
    str += "<div id='cm-msg-" + INDEX + "' class=\"chat-msg " + type + "\">";
    str += "  <div class=\"d-flex align-items-center justify-content-end\">";
    str += "    <div class=\"mx-10\">";
    str += "      <a href=\"#\" class=\"text-dark hover-primary font-weight-bold\">You</a>";
    str += "      <p class=\"text-muted font-size-12 mb-0\">Just now</p>";
    str += "    </div>";
    str += "    <span class=\"msg-avatar\">";
    str += "      <img src=\"/EMPLOYEE-TRACKING-SYSTEM/Dashboard/components/images/avatar/prz3.png\" class=\"avatar avatar-lg\">";
    str += "    </span>";
    str += "  </div>";
    str += "  <div class=\"cm-msg-text\">" + msg + "</div>";
    str += "</div>";
    $(".chat-logs").append(str);
    $("#cm-msg-" + INDEX).hide().fadeIn(300);
    if (type == 'self') {
      $("#chat-input").val('');
    }
    $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight }, 1000);
  }

  function generate_button_message(response, type) {
    INDEX++;
    var str = "";
    str += "<div id='cm-msg-" + INDEX + "' class=\"chat-msg user\">";
    str += "  <div class=\"d-flex align-items-center\">";
    str += "    <span class=\"msg-avatar\">";
    str += "      <img src=\"/EMPLOYEE-TRACKING-SYSTEM/Dashboard/components/images/avatar/me2.png\" class=\"avatar avatar-lg\">";
    str += "    </span>";
    str += "    <div class=\"mx-10\">";
    str += "      <a href=\"#\" class=\"text-dark hover-primary font-weight-bold\">HRM-Assistant</a>";
    str += "      <p class=\"text-muted font-size-12 mb-0\">Just now</p>";
    str += "    </div>";
    str += "  </div>";
    str += "  <div class=\"cm-msg-text\">" + response + "</div>";
    str += "</div>";
    $(".chat-logs").append(str);
    $("#cm-msg-" + INDEX).hide().fadeIn(300);
    if (type == 'user') {
      $("#chat-input").val('');
    }
    $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight }, 1000);
  }

  // Generate a bot response based on the current question count
  function generate_bot_response(msg, questionCount) {
    var response = '';

    switch (questionCount) {
      case 1:
        response = 'Welcome to HRM-chatbot. How can I assist you today?';
        break;
      case 2:
        response = 'Are you an Existing User or a New User?';
        break;
      case 3:
        response = 'Please provide your name or employee ID.';
        break;
      case 4:
        response = 'Can you tell me your department?';
        break;
      case 5:
        response = 'What issue can I assist you with today? (Leave request, salary query, etc.)';
        break;
      case 6:
        response = 'Have you already submitted your request to HR?';
        break;
      case 7:
        response = 'Would you like to schedule an appointment with HR?';
        break;
      case 8:
        response = 'Please provide a brief description of the issue you’re facing.';
        break;
      case 9:
        response = 'Are you satisfied with HR services? (Yes/No)';
        break;
      case 10:
        response = 'Would you like to raise a complaint or request more information?';
        break;
      case 11:
        response = 'Do you need assistance with company policies?';
        break;
      case 12:
        response = 'Can I assist you with something related to your paycheck?';
        break;
      case 13:
        response = 'Have you checked the employee portal for available resources?';
        break;
      case 14:
        response = 'Would you like to know more about employee benefits?';
        break;
      case 15:
        response = 'Do you need help with your personal information or profile update?';
        break;
      case 16:
        response = 'Would you like a list of upcoming HR events or meetings?';
        break;
      case 17:
        response = 'Are you facing any challenges with HR systems or tools?';
        break;
      case 18:
        response = 'Can I help you with vacation or leave requests?';
        break;
      case 19:
        response = 'Do you need to know more about the company’s health and wellness programs?';
        break;
      case 20:
        response = 'Thank you for your time! Let me know if you need further assistance.';
        break;
      default:
        response = 'I’m sorry, I didn’t understand that. Can you please clarify?';
    }

    return response;
  }

  $(document).delegate(".chat-btn", "click", function() {
    var value = $(this).attr("chat-value");
    var name = $(this).html();
    $("#chat-input").attr("disabled", false);
    generate_message(name, 'self');
  });

}); // End of use strict
