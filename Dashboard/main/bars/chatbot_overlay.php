<div id="chat-box-body">
    <div id="chat-circle" class="waves-effect waves-circle btn btn-circle btn-sm btn-warning l-h-50">
        <div id="chat-overlay"></div>
        <span class="icon-Group-chat fs-18"><i class="fa fa-paper-plane fs-22"></i>
            <span class="path1"></span><span class="path2"></span></span>
    </div>

    <div class="chat-box">
        <div class="chat-box-header p-15 d-flex justify-content-between align-items-center">
            <!--<div class="btn-group">
                <button
                    class="waves-effect waves-circle btn btn-circle btn-primary-light h-40 w-40 rounded-circle l-h-50"
                    type="button" data-bs-toggle="dropdown">
                    <span class="icon-Add-user fs-22"><i class="fa fa-user-plus fs-22"></i><span class="path1"></span><span class="path2"></span></span>
                </button>
                 <div class="dropdown-menu min-w-200">
                    <a class="dropdown-item fs-16" href="#">
                        <i class="fa fa-user-plus me-15"></i> New Group
                    </a>
                    <a class="dropdown-item fs-16" href="#">
                        <i class="fa fa-clipboard me-15"></i> Contacts
                    </a>
                    <a class="dropdown-item fs-16" href="#">
                        <i class="fa fa-users me-15"></i> Groups
                    </a>
                    <a class="dropdown-item fs-16" href="#">
                        <i class="fa fa-phone-alt me-15"></i> Calls
                    </a>
                    <a class="dropdown-item fs-16" href="#">
                        <i class="fa fa-cogs me-15"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item fs-16" href="#">
                        <i class="fa fa-question-circle me-15"></i> Help
                    </a>
                    <a class="dropdown-item fs-16" href="#">
                        <i class="fa fa-bell me-15"></i> Privacy
                    </a>
                </div> 
            </div>-->
            <div class="text-center flex-grow-1">
                <div class="text-dark fs-18">Isamel B</div>
                <div>
                    <span class="badge badge-sm badge-dot badge-primary"></span>
                    <span class="text-muted fs-12">Active</span>
                </div>
            </div>
            <div class="chat-box-toggle">
                <button id="chat-box-toggle"
                    class="waves-effect waves-circle btn btn-circle btn-danger-light h-40 w-40 rounded-circle l-h-50"
                    type="button">
                    <span class="fa fa-times fs-22"><span class="path1"></span><span class="path2"></span></span>
                </button>
            </div>
        </div>
        <div class="chat-box-body">
            <div class="chat-box-overlay">
            </div>
            <div class="chat-logs">
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                        <span class="msg-avatar">
                            <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/components/images/avatar/me2.png" class="avatar avatar-lg" alt="">
                        </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Isamel</a>
                            <p class="text-muted fs-12 mb-0">2 Hours</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Hi there, I'm Isamel and you?
                    </div>
                </div>
                <div class="chat-msg self">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">You</a>
                            <p class="text-muted fs-12 mb-0">3 minutes</p>
                        </div>
                        <span class="msg-avatar">
                            <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/components/images/avatar/prz3.png" class="avatar avatar-lg" alt="">
                        </span>
                    </div>
                    <div class="cm-msg-text">
                        My name is Praise M.
                    </div>
                </div>
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                        <span class="msg-avatar">
                            <img src="/EMPLOYEE-TRACKING-SYSTEM/Dashboard/components/images/avatar/me2.png" class="avatar avatar-lg" alt="">
                        </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Isamel-Assistant</a>
                            <p class="text-muted fs-12 mb-0">40 seconds</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Nice to meet you Praise.<br>How can i help you?
                    </div>
                </div>
            </div>
            <!--chat-log -->
        </div>
        <div class="chat-input">
            <form>
                <input type="text" id="chat-input" placeholder="Send a message..." />
                <button type="submit" class="chat-submit" id="chat-submit">
                    <span class="fa fa-paper-plane fs-22"></span>
                </button>
            </form>
        </div>
    </div>
</div>