def login_with_user_details
  p $uname
  p $pass
  fill_in('WSU Access ID', :with => $uname)
  fill_in('password', :with => $pass)
  find(:id, 'sign_in_btn').click
  verify_if_user_logged_in
end


def verify_if_user_logged_in
  find(:id, 'sign_out_btn')
end
