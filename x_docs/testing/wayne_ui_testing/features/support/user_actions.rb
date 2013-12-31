def user_fills_registration_page_with_correct_details
  @user_details = UsernameGenerator.new
  @user_name = @user_details.get_user_id
  @email = @user_name + "@" + "dispostable.com"
  @user_first_name = @user_details.get_first_name
  @user_last_name = @user_details.get_last_name
  @user_reg = AdminRegistrationPage.new()
  @user_reg.register_user(@email,@user_first_name, @user_last_name, @correct_password)
end

def user_fills_registration_page_with_incorrect_email
  @user_details = UsernameGenerator.new
  @user_name = @user_details.get_user_id
  @email = @user_name + "#" + "dispostable.com"
  @user_first_name = @user_details.get_first_name
  @user_last_name = @user_details.get_last_name
  @user_reg = AdminRegistrationPage.new()
  @user_reg.register_user(@email,@user_first_name, @user_last_name, @correct_password)
end

def user_fills_registration_page_with_incorrect_password
  @user_details = UsernameGenerator.new
  @user_name = @user_details.get_user_id
  @email = @user_name + "@" + "dispostable.com"
  @user_first_name = @user_details.get_first_name
  @user_last_name = @user_details.get_last_name
  @user_reg = AdminRegistrationPage.new()
  @user_reg.register_user(@email,@user_first_name, @user_last_name, @incorrect_password)
end

def user_tries_to_register_with_already_registered_user
  @user_details = UsernameGenerator.new
  @user_name = @user_details.get_user_id
  @email = @user_name + "@" + "dispostable.com"
  @user_first_name = @user_details.get_first_name
  @user_last_name = @user_details.get_last_name
  @user_reg = AdminRegistrationPage.new()
  @user_reg.register_user(@email,@user_first_name, @user_last_name, @correct_password)
  user_click_a_button
  if check_if_user_received_email
    @user_reg.register_user(@email,@user_first_name, @user_last_name, @correct_password)
  end
end

def user_clicks_a_button(button_to_click)
  click_button(button_to_click)
end